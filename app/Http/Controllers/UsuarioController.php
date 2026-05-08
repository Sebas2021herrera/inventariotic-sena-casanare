<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('role')->orderBy('name')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required', 'in:admin,tecnico'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Ya existe un usuario con ese correo.',
            'role.required'     => 'Debes seleccionar un rol.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed'=> 'La confirmación no coincide.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => $request->password,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario {$request->name} creado correctamente.");
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $nombre = $usuario->name;
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario {$nombre} eliminado.");
    }
}
