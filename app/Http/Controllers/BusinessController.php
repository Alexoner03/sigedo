<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Heading;
use App\Models\Position;
use App\Models\User;
use App\Traits\RequestTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;

class BusinessController extends Controller
{
    use RequestTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);
        $business = Business::where('id','<>',1)->where('state','<>',0)->with('heading')->get();

        return Inertia::render('Business/Index', [
            'businesses' => $business
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('isAdmin', User::class);

        return Inertia::render('Business/Create', [
            'headings' => Heading::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'business_name' => 'required|unique:businesses,id|string|max:255',
            'ruc'           => 'required|regex:/^[0-9]{11}+$/|unique:businesses,ruc',
            'address'       => 'required|string|max:255',
            'contact_number'=> 'required|numeric|regex:/^9[0-9]{8}+$/',
            'heading_id'    => 'required|numeric',
        ]);

        Business::create($fields);

        $this->flashSuccess("Empresa creada correctamente");

        return redirect()->route('business.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Inertia\Response
     */
    public function edit(Business $business)
    {
        return Inertia::render('Business/Edit',[
            'business' => $business,
            'headings' => Heading::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Business $business)
    {
        $fields = $request->validate([
            'business_name' => "required|unique:businesses,id,{$business->id}|string|max:255",
            'address'       => 'required|string|max:255',
            'contact_number'=> 'required|numeric|regex:/^9[0-9]{8}+$/',
            'heading_id'    => 'required|numeric',
        ]);


        $business->update($fields);

        $this->flashSuccess("Empresa actualizada correctamente");

        return redirect()->route('business.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,Business $business): \Illuminate\Http\RedirectResponse
    {
        $auth = Auth::user();

        if (Hash::check($request->password, $auth->password)) {
            $business->update(['state' => false]);
            $this->flashSuccess("El cliente ha sido eliminado");
            return redirect()->route('business.index');
        }

        return back()->withErrors(new MessageBag(['password' => ['Credenciales incorrectas']]));
    }

    public function getEmployeeByBusinessAndPosition(Business $business, Position $position): \Illuminate\Http\JsonResponse
    {

        $user = auth()->user();
        $employees = User::where('business_id',$business->id)
                         ->where('position_id',$position->id)
                         ->where('id','<>',$user->id)
                         ->where('state','<>',0)
                         ->orderBy('name','ASC')
                         ->with('position')
                         ->get();

        return response()->json($employees);
    }
}
