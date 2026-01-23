<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        // Verifica si ya está logueado para no mostrar el login innecesariamente
        if (Auth::check()) {
            return redirect()->route('dispositivos.index');
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
            
            // intended() intentará llevar al usuario a donde quería ir, 
            // de lo contrario lo manda al index de dispositivos bajo /gitic
            return redirect()->intended(route('dispositivos.index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros del equipo técnico.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request) {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirige específicamente a la ruta de login del prefijo /gitic
        return redirect()->route('login');
    }
}