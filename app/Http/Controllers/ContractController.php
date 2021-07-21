<?php

namespace App\Http\Controllers;

use App\Mail\Message;
use App\Models\Business;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Position;
use App\Models\Record;
use App\Models\User;
use App\Traits\RequestTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContractController extends Controller
{
    use RequestTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $contracts = Contract::with('users','users.position','users.business','documents','records','userCreator','userAssigned','business','contract_type')
                             ->where('id_user_creator',$user->id)
                             ->orWhereHas('users', function($query) use ($user){
                                $query->where('user_id',$user->id);
                             })
                             ->get();
        return Inertia::render('Contract/Index',[
            'contracts' => $contracts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $businesses = Business::orderBy('business_name','ASC')->get();
        $positions = Position::all();

        return Inertia::render('Contract/Create', [
            'businesses' => $businesses,
            'positions' => $positions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            "files" => 'array|required|min:1',
            "files.*" => 'file',
            "reviewers" => 'required|array|min:1',
            "reviewers.*" => 'numeric|exists:users,id',
            "business_id" => 'numeric|exists:businesses,id'
        ],);

        $creator = auth()->user();
        $date = new Carbon();

        $contract = Contract::create([
            'request_date' => $date->now(),
            'id_user_assigned' => $fields['reviewers'][0],
            'id_user_creator' => $creator->id,
            'business_id' => $fields['business_id'],
        ]);

        $contract->code = "{$date->year}{$fields['business_id']}{$contract->id}";

        Record::create([
            'description' => 'Se registró el contrato',
            'contract_id' => $contract->id,
        ]);

        $files = $request->file('files');

        if($request->hasFile('files'))
        {
            foreach ($files as $key => $file) {
                $path = $file->store( 'public/documents' );

                Document::create([
                    'path' => substr( $path, 7 ),
                    'file_name' => $file->getClientOriginalName(),
                    'is_main' => $key === 0 ? true : false,
                    'contract_id' => $contract->id,
                ]);
            }
        }


        foreach ($fields['reviewers'] as $key => $reviewer) {

            $user = User::find($reviewer);

            if ($key === 0) //enviar mail al primer revisor
            {
                Mail::to($user->email)->queue(new Message($user, $contract));
            }

            $contract->users()->attach($user);
        }

        $contract->save();

        $this->flashSuccess("El contrato ha sido ingresado correctamente");

        return redirect()->route('contract.welcome');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function welcome()
    {
        return Inertia::render('Contract/Welcome');
    }

}
