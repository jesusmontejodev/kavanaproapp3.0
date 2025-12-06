<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function create(Request $request, $referral_id = null): View
    {
        return view('auth.register', [
            'referral_id' => $referral_id
        ]);
    }

    /**
     * Procesar registro.
     */
    public function store(Request $request, $referral_id = null): RedirectResponse
    {
        // VALIDACIÓN
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Campos adicionales
            'phone'    => ['nullable', 'string', 'max:50'],
            'estado'   => ['nullable', 'string', 'max:100'],
            'ciudad'   => ['nullable', 'string', 'max:100'],
            'empresa'  => ['nullable', 'string', 'max:255'],
        ]);

        // CREAR USUARIO
        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => Hash::make($request->password),

            'phone'    => $request->phone,
            'estado'   => $request->estado,
            'ciudad'   => $request->ciudad,
            'empresa'  => $request->empresa,
        ]);

        // RECUPERAR referral_id (desde URL o desde el formulario)
        $ref = $referral_id ?? $request->referral_id;

        // GUARDAR REFERIDO (si existe)
        if ($ref) {
            DB::table('registro_referidos')->insert([
                'user_id'      => $user->id,  // nuevo usuario
                'referido_por' => $ref,       // dueño del link
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // Evento de registro
        event(new Registered($user));

        // Auto login
        Auth::login($user);

        return redirect()->route('home');
    }
}
