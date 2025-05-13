<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 1. Mostrar listado de usuarios (para DataTables cliente)
    public function index()
    {
        // Traemos todos los usuarios para que DataTables haga
        // paginación, búsqueda y orden en el cliente.
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
