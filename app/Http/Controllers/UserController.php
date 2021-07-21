<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Role;
use App\Traits\RequestTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Inertia\Inertia;

class UserController extends Controller
{

    use PasswordValidationRules, RequestTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);

        $users = User::where('id', '!=', auth()->id())->get();

        return Inertia::render('Users/Index', [
            'users' => $users
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

        return Inertia::render('Users/Create', [
            'roles' => Role::all()
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
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'password' => $this->passwordRules(),
        ])->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        $this->flashSuccess("Usuario creado correctamente");

        return redirect()->route('user.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('isAdmin', User::class);

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $fields = $request->validate([
            'name' => 'string|required|min:3',
            'email' => 'email|required|unique:users,email,' . $user->id,
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
        ]);

        $user->update($fields);

        $this->flashSuccess("El usuario ha sido actualizado");

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $auth = Auth::user();

        if (Hash::check($request->password, $auth->password)) {
            $user->delete();
            $this->flashSuccess("El usuario ha sido eliminado");
            return redirect()->route('user.index');
        }
        
        return back()->withErrors(new MessageBag(['password' => ['Credenciales incorrectas']]));
    }
}
