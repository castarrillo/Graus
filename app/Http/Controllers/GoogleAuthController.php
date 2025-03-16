<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User; // tu modelo de usuario
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GoogleAuthController extends Controller
{
  /**
   * Redirige al usuario a la página de autenticación de Google.
   */
  public function redirectToGoogle()
  {
    // Redirige al usuario a Google para autenticar
    return Socialite::driver('google')->redirect();
  }

  /**
   * Maneja la respuesta que envía Google tras la autenticación.
   */
  public function handleGoogleCallback(Request $request)
  {
    // Utiliza stateless() para no depender de las sesiones (ideal para APIs)
    $googleUser = Socialite::driver('google')->stateless()->user();

    // Busca si el usuario ya existe en la base de datos
    $user = User::where('email', $googleUser->getEmail())->first();

    // Si el usuario no existe, créalo con datos básicos
    if (!$user) {
      $user = User::create([
        'name'     => $googleUser->getName() ?: $googleUser->getNickname(),
        'email'    => $googleUser->getEmail(),
        // Contraseña dummy / aleatoria
        'password' => Hash::make(Str::random(24)),
        // Ajusta el rol por defecto, por ejemplo, Estudiante (role_id = 2)
        'role_id'  => 2,
        // Activar la cuenta
        'enabled'  => 1,
      ]);
    }

    // Genera token JWT con el guard 'api'
    if (!$token = auth('api')->login($user)) {
      return response()->json(['error' => 'No fue posible generar el token'], 500);
    }

    // Retorna el token al cliente o redirige al frontend
    return response()->json([
      'mensaje'      => 'Inicio de sesión con Google exitoso',
      'user'         => $user,
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => time() + (60 * 60),
    ], 200);
  }
}
