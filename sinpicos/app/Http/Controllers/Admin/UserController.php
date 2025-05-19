<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\ControlAdmin;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(ControlAdmin::class); // Middleware para verificar rol
    }

    // 1. Mostrar listado de usuarios (para DataTables cliente)
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Formulario de creación
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. Guardar nuevo usuario
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|string|confirmed|min:8',
            'rol'            => 'required|string',
            'tipo_diabetes'  => 'required|string',
        ], [
            'name.required'           => 'El nombre es obligatorio.',
            'name.max'                => 'El nombre no puede exceder los :max caracteres.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.email'             => 'Debes introducir un correo válido.',
            'email.unique'            => 'Ese correo ya está registrado.',
            'password.required'       => 'La contraseña es obligatoria.',
            'password.min'            => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed'      => 'Las contraseñas no coinciden.',
            'rol.required'            => 'Debes asignar un rol al usuario.',
            'tipo_diabetes.required'  => 'Debes indicar el tipo de diabetes.',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // 4. Ver un usuario
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // 5. Formulario de edición
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 6. Actualizar usuario
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => "required|email|unique:users,email,{$user->id}",
            'password'       => 'nullable|string|confirmed|min:8',
            'rol'            => 'required|string',
            'tipo_diabetes'  => 'required|string',
        ], [
            'name.required'           => 'El nombre es obligatorio.',
            'name.max'                => 'El nombre no puede exceder los :max caracteres.',
            'email.required'          => 'El correo es obligatorio.',
            'email.email'             => 'Introduce un correo válido.',
            'email.unique'            => 'Ese correo ya está en uso.',
            'password.min'            => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed'      => 'Las contraseñas no coinciden.',
            'rol.required'            => 'Selecciona un rol.',
            'tipo_diabetes.required'  => 'Selecciona el tipo de diabetes.',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // 7. Borrar usuario
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
