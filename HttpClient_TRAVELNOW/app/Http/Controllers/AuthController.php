<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // ✅ CORREGIDO: Lee desde env('TRAVELNOW_API_URL') — funciona en local y producción
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(env('TRAVELNOW_API_URL', 'http://127.0.0.1:8000/api'), '/') . '/cliente';
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = Http::post("{$this->apiUrl}/login", [
                'correo' => $request->correo,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                session()->put('token', $data['access_token']);
                session()->put('user', $data['cliente']);
                
                return redirect()->intended('/')->with('success', 'Has iniciado sesión correctamente.');
            }

            return back()->with('error', 'Credenciales incorrectas.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al conectar con el servidor.');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            // ✅ CORREGIDO: usa $this->apiUrl para funcionar en producción
            $response = Http::post("{$this->apiUrl}/register", [
                'nombre'   => $request->nombre,
                'correo'   => $request->correo,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Guardar sesión igual que en login
                session()->put('token', $data['access_token']);
                session()->put('user', $data['cliente']);
                return redirect('/')
                    ->with('success', '¡Cuenta creada! Bienvenido/a, ' . $data['cliente']['nombre']);
            }

            $errors = $response->json('errors', []);
            return back()->withErrors($errors)->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Error al conectar con el servidor. Intenta más tarde.');
        }
    }

    public function logout()
    {
        try {
            Http::withToken(session('token'))->post("{$this->apiUrl}/logout");
        } catch (\Exception $e) {
            // Ignorar si la API no responde
        }

        session()->forget('token');
        session()->forget('user');
        session()->forget('cart'); // Limpiar carrito por seguridad

        return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }

    public function perfil()
    {
        if (!session('token')) return redirect('/login');

        try {
            $response = Http::withToken(session('token'))->get("{$this->apiUrl}/perfil");
            if ($response->successful()) {
                return view('auth.perfil', ['user' => $response->json()]);
            }
        } catch (\Exception $e) {
            // Error handling
        }
        
        return redirect('/login')->with('error', 'Tu sesión ha expirado.');
    }

    public function updatePerfil(Request $request)
    {
        if (!session('token')) return redirect('/login');

        try {
            $data = $request->only(['nombre', 'correo', 'telefono']);
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $response = Http::withToken(session('token'))->put("{$this->apiUrl}/perfil", $data);

            if ($response->successful()) {
                session()->put('user', $response->json()['cliente']);
                return back()->with('success', 'Perfil actualizado correctamente.');
            }
            
            return back()->with('error', 'No se pudo actualizar el perfil.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión.');
        }
    }
}
