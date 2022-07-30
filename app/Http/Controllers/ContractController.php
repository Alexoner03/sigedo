<?php

namespace App\Http\Controllers;

use App\Mail\Message;
use App\Models\Business;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Document;
use App\Models\Position;
use App\Models\Record;
use App\Models\User;
use App\Traits\RequestTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;

class ContractController extends Controller
{
    use RequestTrait;

    public function index()
    {
        $user = auth()->user();
        $slaves_raw = User::where('supervisor_to_report',$user->id)->get();
        $slaves = $slaves_raw->map(function (User $_user){
            return $_user->id;
        })->all();

        $contracts = Contract::
            with('users', 'users.position', 'users.business', 'documents', 'records', 'userCreator', 'userAssigned', 'business', 'contract_type')
            ->where('id_user_creator', $user->id)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orWhereHas('users', function ($query) use ($slaves) {
                $query->whereIn('user_id', $slaves);
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return Inertia::render('Contract/Index', [
            'contracts' => $contracts
        ]);
    }

    public function create()
    {
        $user = auth()->user();

        $businesses = $user->business_id == 1 ? Business::where('id','<>', 1)->get() : Business::where('id', 1)->get();
        $positions = $user->business_id == 1 ? Position::where('id','<>', 1)->get() : Position::where('id', 1)->get();

        return Inertia::render('Contract/Create', [
            'businesses' => $businesses,
            'positions' => $positions,
        ]);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            "files" => 'array|required|min:1',
            "files.*" => 'file',
            "reviewers" => 'required|array|min:1',
            "reviewers.*" => 'numeric|exists:users,id',
            "business_id" => 'numeric|required|min:0'
        ]);

        $user = auth()->user();

        $date = new Carbon();

        $contract = Contract::create([
            'request_date' => $date->now(),
            'id_user_assigned' => $fields['reviewers'][0],
            'id_user_creator' => $user->id,
            'business_id' => $fields['business_id']
        ]);

        $contract->code = "{$date->year}{$fields['business_id']}{$contract->id}";

        Record::create([
            'description' => 'Se registró el contrato',
            'contract_id' => $contract->id,
        ]);

        $files = $request->file('files');

        if ($request->hasFile('files')) {
            foreach ($files as $key => $file) {
                $path = $file->store('public/documents');

                Document::create([
                    'path' => substr($path, 7),
                    'file_name' => $file->getClientOriginalName(),
                    'is_main' => $key == 0 ? true : false,
                    'contract_id' => $contract->id,
                ]);
            }
        }


        foreach ($fields['reviewers'] as $key => $reviewer) {

            $user = User::find($reviewer);

            if ($key == 0) //enviar mail al primer revisor
            {
//                Mail::to($user->email)->queue(new Message($user, $contract, 'new'));
                $this->notify($contract,$user,'new');
            }

            $contract->users()->attach($user);
        }

        $contract->save();

        $this->flashSuccess("El contrato ha sido ingresado correctamente");

        return redirect()->route('contract.welcome');
    }

    public function welcome()
    {
        return Inertia::render('Contract/Welcome');
    }

    public function review(Contract $contract)
    {
        if (auth()->user()->id != $contract->id_user_assigned) {
            $this->flashError("Usted no está asignado para revisar ese documento");
            return redirect()->route('contract.welcome');
        }

        $contract->load('documents');

        return Inertia::render('Contract/Review', [
            'contract' => $contract
        ]);
    }

    public function updateReview(Request $request, Contract $contract)
    {
        $user = auth()->user();

        if ($user->id != $contract->id_user_assigned) {
            $this->flashError("Usted no está asignado para revisar ese documento");
            return redirect()->route('contract.welcome');
        }

        $fields = $request->validate([
            "obs" => 'boolean|required', //existe observacion?
            "message" => 'nullable|string|required_if:obs,true',
        ]);
        $creator = $contract->userCreator;


        if ($fields["obs"]) {


//            Mail::to($creator->email)->queue(new Message($creator, $contract,'observe'));
            $this->notify($contract, $creator,'observe');

            $contract->userAssigned->contracts()->updateExistingPivot($contract->id, ["observations" => $fields['message']]);

            Record::create([
                'description' => "{$contract->userAssigned->name} encontró observaciones",
                'contract_id' => $contract->id,
            ]);

            //devolviendo a creador
            $contract->id_user_assigned = $contract->id_user_creator;
        } else {

            $keyOfUserAssigned  = 0; //setearemos la posición del usuario asignado actual en la lista de revisores

            foreach ($contract->users as $key => $user) {

                if ($contract->id_user_assigned == $user->id) {
                    $keyOfUserAssigned = $key;
                    $user->contracts()->updateExistingPivot($contract->id, ["check" => true]);
                }
            }

            Record::create([
                'description' => "{$contract->userAssigned->name} ha aprobado el documento",
                'contract_id' => $contract->id,
            ]);

            //validando si es el ultimo en la lista si no lo devolvemos al reviewer para que lo finalize
            if ($contract->users->count() === ($keyOfUserAssigned + 1)) {
                $contract->id_user_assigned = $contract->id_user_creator;
                $contract->state = 'archivado';

                Record::create([
                    'description' => "finalizó la revisión del contrato",
                    'contract_id' => $contract->id,
                ]);

//                Mail::to($creator->email)->queue(new Message($creator, $contract,'finish'));
                $this->notify($contract, $creator,'finish');


            } else {
                $newReviewer = $contract->users[$keyOfUserAssigned + 1];
                $contract->id_user_assigned = $newReviewer->id;

//                Mail::to($newReviewer->email)->queue(new Message($newReviewer, $contract, 'new'));
                $this->notify($contract, $newReviewer,'new');

            }
        }

        $contract->save();

        $this->flashSuccess("El contrato ha sido actualizado correctamente");

        return redirect()->route('contract.welcome');
    }

    public function correct(Contract $contract)
    {
        if (auth()->user()->id != $contract->id_user_creator) {
            $this->flashError("Usted no está asignado para corregir ese documento");
            return redirect()->route('contract.welcome');
        }

        $contract->load('documents');

        return Inertia::render('Contract/Correct.vue', [
            'contract' => $contract
        ]);
    }

    public function updateCorrect(Request $request, Contract $contract)
    {
        if (auth()->user()->id != $contract->id_user_creator) {
            $this->flashError("Usted no está asignado para corregir ese documento");
            return redirect()->route('contract.welcome');
        }

        $request->validate([
            "file" => 'file|required',
        ]);

        $contract->load('documents', 'users');

        $keyOfLastUserCheck = -1;

        foreach ($contract->users as $key => $user) {

            if ($user->pivot->check == 0) {
                $keyOfLastUserCheck = $key;
                break;
            }
        }

        $lastUserCheck = $contract->users[$keyOfLastUserCheck];

        //devolviendo al ultimo revisor sin check
        $contract->id_user_assigned = $lastUserCheck->id;

//        Mail::to($lastUserCheck->email)->queue(new Message($lastUserCheck, $contract,'new'));
        $this->notify($contract, $lastUserCheck,'new');



        foreach ($contract->documents as $document) {
            if ($document->is_main == 1) {
                Storage::delete("public/{$document->path}");
                $_path = $request->file('file')->store('public/documents');
                $document->path = substr($_path, 7);
                $document->save();
            }
        }

        Record::create([
            'description' => "{$contract->userCreator->name} ha subido correcciones",
            'contract_id' => $contract->id,
        ]);

        $contract->save();

        $this->flashSuccess("El contrato ha sido actualizado correctamente");

        return redirect()->route('contract.welcome');
    }

    public function finalize(Contract $contract)
    {
        if (auth()->user()->business_id != 1) {
            $this->flashError("Usted no pertenece a sanabria y asociados");
            return redirect()->route('contract.welcome');
        }


        $categories = ContractType::all();
        $contract->load('business', 'userCreator');

        return Inertia::render('Contract/Finalize.vue', [
            'contract' => $contract,
            'categories' => $categories
        ]);
    }

    public function updateFinalize(Request $request, Contract $contract)
    {
        $user = auth()->user();

        if ($user->business_id != 1) {
            $this->flashError("Usted no pertenece a sanabria y asociados");
            return redirect()->route('contract.welcome');
        }


        $fields = $request->validate([
            'objective' => 'string|required',
            'resolution' => 'string|required',
            'observations' => 'string|required',
            'contract_type_id' => 'numeric|required',
        ]);

        $fields['term_date'] = Carbon::now();

        $contract->update($fields);

        Record::create([
            'description' => "{$user->name} ha finalizado el contrato",
            'contract_id' => $contract->id,
        ]);

        $this->flashSuccess("El contrato ha sido finalizado correctamente");

        return redirect()->route('contract.welcome');
    }

    public function forceUpdateFinalize(Request $request, Contract $contract)
    {
        $_user = auth()->user();

        if ($_user->business_id != 1) {
            $this->flashError("Usted no pertenece a sanabria y asociados");
            return redirect()->route('contract.welcome');
        }

        Record::create([
            'description' => "{$_user->name} ha forzado finalización del contrato",
            'contract_id' => $contract->id,
        ]);

        $fields = $request->validate([
            'objective' => 'string|required',
            'resolution' => 'string|required',
            'observations' => 'string|required',
            'contract_type_id' => 'numeric|required',
        ]);


        foreach ($contract->users as $user) {
            $user->contracts()->updateExistingPivot($contract->id, ["check" => true]);
        }

        $fields['term_date'] = Carbon::now();
        $fields['state'] = 'archivado';

        $contract->update($fields);

        $this->flashSuccess("El contrato ha sido finalizado correctamente");

        return redirect()->route('contract.welcome');
    }

    private function notify(Contract $contract,$user,String $type){

        $mail = Mail::to($user->email);
        $contract->load('business');
        $gerentes_generales_raw = User::where('position_id',2)->where('business_id',$contract->business->id)->get();
        $gerentes_generales = $gerentes_generales_raw->map(function($item){ return $item->email; })->all();

        //check if is executor
        if($user->role_id === 2){
            $user->load('supervisorToReport');
            array_push($gerentes_generales,$user->supervisorToReport->email);
        }

        $mail->cc($gerentes_generales);

        $mail->queue(new Message($user, $contract, $type));

    }
}
