<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Mostrar listado
    public function index()
    {
        $users = User::paginate(10);
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
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // 4. Formulario de edición
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 5. Actualizar usuario
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => "required|email|unique:users,email,{$user->id}",
            'password'       => 'nullable|string|confirmed|min:8',
            'rol'            => 'required|string',
            'tipo_diabetes'  => 'required|string',
        ]);

        if ($data['password'] ?? false) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // 6. Borrar usuario
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
