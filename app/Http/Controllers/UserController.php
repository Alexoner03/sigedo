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

        $users = User::where('id', '<>', auth()->id())->with('business','position','supervisorToReport')->get();

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
            'positions' => Position::where('id','<>',1)->get(),
            'supervisors' => User::where('role_id',1)->get(),
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
            'supervisor_to_report' => ['nullable','required_if:role_id,2','numeric','exists:users,id'],
            'password' => $this->passwordRules(),
        ])->validate();

        $new_user = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'dni' => $request->dni,
            'password' => Hash::make($request->password),
            'position_id' => $request->business_id === 1 ? 1 : $request->position_id,
            'business_id' => $request->business_id,
        ];

        if($request->role_id === 2){
            $new_user['supervisor_to_report'] = $request->supervisor_to_report;
        }

        User::create($new_user);

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
            'roles' => Role::all(),
            'businesses' => Business::all(),
            'positions' => Position::where('id','<>',1)->get(),
            'supervisors' => User::where('role_id',1)->get(),
            'user' => $user
        ]);
    }


    /**
     * @throws ValidationException
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id."id"],
            'dni' => 'required|numeric|regex:/^[0-9]{8}+$/|unique:users,dni,'.$user->id."id",
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'position_id' => ['required', 'numeric', 'exists:positions,id'],
            'business_id' => ['required', 'numeric', 'exists:businesses,id'],
            'supervisor_to_report' => ['nullable','required_if:role_id,2','numeric','exists:users,id'],
        ])->validate();

        $edit_user = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'dni' => $request->dni,
            'position_id' => $request->business_id === 1 ? 1 : $request->position_id,
            'business_id' => $request->business_id,
            'supervisor_to_report' => $request->role_id === 2 ? $request->supervisor_to_report : null,
        ];

        $user->update($edit_user);

        $this->flashSuccess("El usuario ha sido actualizado");

        return redirect()->route('user.index');
    }


    public function destroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $auth = Auth::user();
        if (Hash::check($request->password, $auth->getAuthPassword())) {
            $user->state = false;
            $user->save();
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
