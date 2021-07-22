<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Heading;
use App\Models\Position;
use App\Models\User;
use App\Traits\RequestTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BusinessController extends Controller
{
    use RequestTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);

        return Inertia::render('Business/Index', [
            'businesses' => Business::with('headings')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'ruc' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['required', 'numeric', 'exists:roles,id'],
            'contact_number' => ['required', 'numeric', 'exists:positions,id'],
            'heading_id' => ['required', 'numeric', 'exists:businesses,id'],
        ]);

        Business::create($fields);

        $this->flashSuccess("Empresa creada correctamente");

        return redirect()->route('business.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        //
    }

    public function getEmployeeByBusinessAndPosition(Business $business, Position $position)
    {

        $user = auth()->user();
        $employees = User::where('business_id',$business->id)
                         ->where('position_id',$position->id)
                         ->where('id','<>',$user->id)
                         ->orderBy('name','ASC')
                         ->with('position')
                         ->get();

        return response()->json($employees);
    }
}
