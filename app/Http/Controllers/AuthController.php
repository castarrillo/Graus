<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Twilio\Rest\Client;

class AuthController extends Controller
{
  /**
   * Se aplica el middleware "auth:api" a todos los métodos,
   * excepto login, register, sendResetLink, sendResetCode, resetPassword y sendTestSms.
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'register', 'sendResetLink', 'sendResetCode', 'resetPassword', 'sendTestSms']]);
  }

  /**
   * Inicia sesión y retorna un token JWT.
   */
  public function login()
  {
    $credentials = request(['email', 'password']);

    if (!$token = auth('api')->attempt($credentials)) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
   * Retorna el perfil del usuario autenticado.
   */
  public function profile()
  {
    return response()->json(Auth::user());
  }

  /**
   * Cierra la sesión del usuario.
   */
  public function logout()
  {
    Auth::logout();
    return response()->json(['message' => 'Sesión cerrada correctamente']);
  }

  /**
   * Refresca el token JWT.
   */
  public function refresh()
  {
    return $this->respondWithToken(Auth::refresh());
  }

  /**
   * Formatea y retorna el token JWT.
   *
   * @param string $token
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => time() + (60 * 60 * 24)
    ]);
  }

  /**
   * Registra un nuevo usuario.
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required|string',
      'email'    => 'required|string|email|unique:users',
      'password' => 'required|string|min:8'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }

    $user = User::create(array_merge(
      $validator->validated(),
      ['password' => Hash::make($request->password)]
    ));

    $token = auth('api')->login($user);

    return response()->json([
      'message'      => 'Usuario registrado con éxito',
      'user'         => $user,
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => time() + (60 * 60 * 24)
    ], 201);
  }

  /* ====================================================
    CRUD Completo para la Gestión de Usuarios (sólo Administrador)
  ==================================================== */

  /**
   * Obtiene la lista de usuarios.
   */
  public function index()
  {
    $this->checkRole('Administrador');
    $usuarios = User::all();
    return response()->json($usuarios, 200);
  }

  /**
   * Muestra el detalle de un usuario por ID.
   *
   * @param int $id
   */
  public function show($id)
  {
    $this->checkRole('Administrador');
    $usuario = User::findOrFail($id);
    return response()->json($usuario, 200);
  }

  /**
   * Actualiza un usuario (solo Administrador).
   *
   * Permite modificar cualquier campo enviado en el request.
   *
   * @param Request $request
   * @param int $id
   */
  public function update(Request $request, $id)
  {
    $this->checkRole('Administrador');
    $usuario = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'name'          => 'sometimes|required|string',
      'email'         => 'sometimes|required|email|unique:users,email,' . $usuario->id,
      'password'      => 'sometimes|required|string|min:6',
      'role_id'       => 'sometimes|required|exists:roles,id',
      'colegio_id'    => 'sometimes|nullable|exists:colegios,id',
      'enabled'       => 'sometimes|required|boolean',
      'nivel'         => 'sometimes|nullable|in:principiante,intermedio,avanzado',
      'phone'         => 'sometimes|nullable|string',
      'date_of_birth' => 'sometimes|nullable|date',
      'gender'        => 'sometimes|nullable|in:masculino,femenino,otro'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $data = $validator->validated();
    if (isset($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    }

    $usuario->update($data);

    return response()->json(['mensaje' => 'Usuario actualizado', 'usuario' => $usuario], 200);
  }

  /**
   * Deshabilita un usuario (solo Administrador).
   *
   * @param int $id
   */
  public function destroy($id)
  {
    $this->checkRole('Administrador');
    $usuario = User::findOrFail($id);
    $usuario->update(['enabled' => 0]);
    return response()->json(['mensaje' => 'Usuario deshabilitado'], 200);
  }

  /* ====================================================
    Funcionalidad para Restablecimiento de Contraseña
    Soporta el envío del código por correo electrónico o por SMS.
  ==================================================== */

  /**
   * Envía el enlace de reseteo de contraseña por correo electrónico.
   *
   * Valida el email y genera un token que se almacena en la tabla password_reset_tokens,
   * luego envía un correo con el enlace para restablecer la contraseña.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetLink(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email'
    ]);

    $email = $request->email;
    $token = Str::random(60);

    // Almacenar o actualizar el token en la tabla password_reset_tokens
    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $email],
      [
        'token'      => $token, // Se recomienda encriptar este token en producción
        'created_at' => Carbon::now()
      ]
    );

    // Enviar correo (configura tu sistema de correo y la vista del email)
    Mail::send('emails.password_reset', ['token' => $token, 'email' => $email], function ($message) use ($email) {
      $message->to($email);
      $message->subject('Restablecimiento de contraseña');
    });

    return response()->json(['mensaje' => 'Se ha enviado un enlace para restablecer la contraseña a su correo.'], 200);
  }

  /**
   * Envía el código de restablecimiento de contraseña por SMS.
   *
   * Valida el email y verifica que el usuario tenga un número de teléfono registrado.
   * Genera un código de 6 dígitos, lo almacena en la tabla password_reset_tokens
   * y lo envía al teléfono del usuario utilizando Twilio.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetCode(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user->phone) {
      return response()->json(['error' => 'El usuario no tiene un número de teléfono registrado.'], 404);
    }

    // Genera un código numérico de 6 dígitos
    $code = rand(100000, 999999);

    // Almacena o actualiza el código en la tabla password_reset_tokens
    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $request->email],
      [
        'token'      => $code,
        'created_at' => Carbon::now()
      ]
    );

    // Enviar SMS usando Twilio
    try {
      $sid = config('services.twilio.sid');
      $tokenTwilio = config('services.twilio.token');
      $from = config('services.twilio.from');

      $client = new Client($sid, $tokenTwilio);
      $client->messages->create(
        $user->phone,
        [
          'from' => $from,
          'body' => "Tu código para restablecer la contraseña es: $code"
        ]
      );
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error enviando SMS: ' . $e->getMessage()], 500);
    }

    return response()->json(['mensaje' => 'Se ha enviado un código para restablecer la contraseña a tu teléfono.'], 200);
  }

  /**
   * Restablece la contraseña del usuario.
   *
   * Valida que el email, el código (ya sea enviado por correo o SMS)
   * y la nueva contraseña sean correctos. Actualiza la contraseña del usuario
   * y elimina el registro en password_reset_tokens.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function resetPassword(Request $request)
  {
    $request->validate([
      'email'    => 'required|email|exists:users,email',
      'token'    => 'required', // Puede ser alfanumérico (correo) o numérico (SMS)
      'password' => 'required|string|min:6|confirmed'
    ]);

    // Buscar el registro en password_reset_tokens
    $record = DB::table('password_reset_tokens')
      ->where('email', $request->email)
      ->first();

    if (!$record) {
      return response()->json(['error' => 'No se encontró un código para este correo.'], 404);
    }

    // Verificar si el código ha expirado (60 minutos de validez)
    if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
      return response()->json(['error' => 'El código ha expirado.'], 422);
    }

    // Convertir el token ingresado a string para la comparación
    if ((string)$record->token !== (string)$request->token) {
      return response()->json(['error' => 'Código inválido.'], 422);
    }

    // Actualizar la contraseña del usuario
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Eliminar el registro del código
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['mensaje' => 'Contraseña actualizada con éxito.'], 200);
  }


  /* ====================================================
    Función para enviar SMS de prueba utilizando Twilio
  ==================================================== */

  /**
   * Envía un mensaje de texto de prueba utilizando Twilio.
   *
   * Este método se utiliza para verificar que la integración con Twilio
   * esté funcionando correctamente.
   *
   * @param Request $request Contiene el número de teléfono de destino y el mensaje.
   *                         El número debe incluir el código de país (e.g., +573001234567).
   * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado del envío.
   */
  public function sendTestSms(Request $request)
  {
    $request->validate([
      'phone'   => 'required|string',
      'message' => 'required|string'
    ]);

    try {
      $sid = config('services.twilio.sid');
      $tokenTwilio = config('services.twilio.token');
      $from = config('services.twilio.from');

      $client = new Client($sid, $tokenTwilio);
      $sms = $client->messages->create($request->phone, [
        'from' => $from,
        'body' => $request->message,
      ]);

      return response()->json([
        'mensaje' => 'Mensaje de prueba enviado exitosamente.',
        'sid'     => $sms->sid
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error enviando el mensaje de prueba: ' . $e->getMessage()], 500);
    }
  }

  /* ====================================================
    Helper: Verificar Rol del Usuario Autenticado
  ==================================================== */

  /**
   * Verifica que el usuario autenticado tenga el rol requerido.
   *
   * @param string $requiredRole
   */
  private function checkRole($requiredRole)
  {
    $usuario = Auth::user();
    if (!$usuario || !$usuario->role) {
      abort(401, 'No autenticado o sin rol asignado');
    }
    if ($usuario->role->name !== $requiredRole) {
      abort(403, "No tienes el rol de $requiredRole");
    }
  }
}
