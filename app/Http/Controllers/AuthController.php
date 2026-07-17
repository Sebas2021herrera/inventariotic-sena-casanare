<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        // Verifica si ya está logueado para no mostrar el login innecesariamente
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo es obligatorio para GITIC.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros del equipo técnico.',
        ])->withInput($request->only('email'));
    }

    public function showCambiarClave() {
        return view('auth.cambiar_clave');
    }

    public function cambiarClave(Request $request) {
        $request->validate([
            'clave_actual'      => ['required'],
            'nueva_clave'       => ['required', 'min:8', 'confirmed'],
        ], [
            'clave_actual.required'     => 'Debes ingresar tu contraseña actual.',
            'nueva_clave.required'      => 'La nueva contraseña es obligatoria.',
            'nueva_clave.min'           => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'nueva_clave.confirmed'     => 'La confirmación no coincide con la nueva contraseña.',
        ]);

        if (!Hash::check($request->clave_actual, Auth::user()->password)) {
            return back()->withErrors(['clave_actual' => 'La contraseña actual no es correcta.']);
        }

        Auth::user()->update(['password' => $request->nueva_clave]);

        return redirect()->route('perfil.cambiar-clave')
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    public function logout(Request $request) {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirige específicamente a la ruta de login del prefijo /gitic
        return redirect()->route('login');
    }
}