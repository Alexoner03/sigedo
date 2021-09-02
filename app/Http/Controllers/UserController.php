<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Business;
use App\Models\Position;
use App\Models\Role;
use App\Traits\RequestTrait;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserController extends Controller
{

    use PasswordValidationRules, RequestTrait;


    /**
     * @throws AuthorizationException
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('isAdmin', User::class);

        $users = User::where('id', '<>', auth()->id())->with('business','position')->get();

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function create(): \Inertia\Response
    {
        $this->authorize('isAdmin', User::class);

        return Inertia::render('Users/Create', [
            'roles' => Role::all(),
            'businesses' => Business::all(),
            'positions' => Position::all(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'dni' => 'required|numeric|regex:/^[0-9]{8}+$/|unique:users,dni',
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'position_id' => ['required', 'numeric', 'exists:positions,id'],
            'business_id' => ['required', 'numeric', 'exists:businesses,id'],
            'password' => $this->passwordRules(),
        ])->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'dni' => $request->dni,
            'password' => Hash::make($request->password),
            'position_id' => $request->position_id,
            'business_id' => $request->business_id,
        ]);

        $this->flashSuccess("Usuario creado correctamente");

        return redirect()->route('user.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(User $user): \Inertia\Response
    {
        $this->authorize('isAdmin', User::class);

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => Role::all(),
            'businesses' => Business::all(),
            'positions' => Position::all(),
        ]);
    }


    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $fields = $request->validate([
            'name' => 'string|required|min:3',
            'email' => 'email|required|unique:users,email,' . $user->id,
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'dni' => [ "required", "numeric", "regex:/^[0-9]{8}+$/",  Rule::unique('users')->ignore($user->dni, "dni")],
            'position_id' => ['required', 'numeric', 'exists:positions,id'],
            'business_id' => ['required', 'numeric', 'exists:businesses,id'],
        ]);

        $user->update($fields);

        $this->flashSuccess("El usuario ha sido actualizado");

        return redirect()->route('user.index');
    }


    public function destroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $auth = Auth::user();
        Log::debug($auth);
        if (Hash::check($request->password, $auth->getAuthPassword())) {
            $user->state = false;
            $resp = $user->save();

            Log::debug("EL guardadado fue {$resp}");
            $this->flashSuccess("El usuario ha sido eliminado");
            return redirect()->route('user.index');
        }

        $this->flashError("Contraseña incorrecta");
        return redirect()->route('user.index');

    }

    public function restore(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $auth = Auth::user();

        if (Hash::check($request->password, $auth->getAuthPassword())) {
            $user->state = true;
            $user->save();
            $this->flashSuccess("El usuario ha sido reestablecido");
            return redirect()->route('user.index');
        }

        $this->flashError("Contraseña incorrecta");
        return redirect()->route('user.index');

    }
}
