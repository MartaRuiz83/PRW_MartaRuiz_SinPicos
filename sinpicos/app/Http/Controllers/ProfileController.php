<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Solo usuarios autenticados
    }

    // Formulario de edición de perfil
    public function edit()
{
    $user = auth()->user();
    return view('profile.edit', compact('user')); // 👈 NUEVA VISTA
}


    // Actualizar datos del perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => "required|email|unique:users,email,{$user->id}",
            'password'       => 'nullable|string|confirmed|min:8',
            'tipo_diabetes'  => 'required|string',
        ], [
            'name.required'           => 'El nombre es obligatorio.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.unique'            => 'Ese correo ya está en uso.',
            'password.min'            => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed'      => 'Las contraseñas no coinciden.',
            'tipo_diabetes.required'  => 'El tipo de diabetes es obligatorio.',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->tipo_diabetes = $data['tipo_diabetes'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('home')->with('success', 'Perfil actualizado correctamente.');
    }
}
