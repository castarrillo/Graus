<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use Twilio\Rest\Client;

class AuthController extends Controller
{
  /**
   * Aplica el middleware "auth:api" a todos los métodos, excepto aquellos que no requieren autenticación:
   * login, register, sendResetEmail, sendResetSMS, sendResetWhatsapp y resetPassword.
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => [
      'login',
      'register',
      'sendResetEmail',
      'sendResetSMS',
      'sendResetWhatsapp',
      'resetPassword'
    ]]);
  }

  /**
   * Inicia sesión y retorna un token JWT.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login()
  {
    // Se obtienen las credenciales del request (email y contraseña)
    $credentials = request(['email', 'password']);

    // Se intenta autenticar al usuario y generar el token JWT
    if (!$token = auth('api')->attempt($credentials)) {
      return response()->json(['error' => 'No autorizado'], 401);
    }

    // Se retorna el token formateado
    return $this->respondWithToken($token);
  }

  /**
   * Retorna el perfil del usuario autenticado.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function profile()
  {
    return response()->json(Auth::user());
  }

  /**
   * Cierra la sesión del usuario.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    Auth::logout();
    return response()->json(['message' => 'Sesión cerrada correctamente']);
  }

  /**
   * Refresca el token JWT.
   *
   * @return \Illuminate\Http\JsonResponse
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
      'user'         => auth('api')->user(),
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => 86400
    ]);
  }

  /**
   * Registra un nuevo usuario.
   *
   * Valida la información enviada en el request y crea un nuevo usuario,
   * encriptando la contraseña. Luego, genera y retorna un token JWT.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
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

    // Se crea el usuario en base a la validación y se encripta la contraseña
    $user = User::create(array_merge(
      $validator->validated(),
      ['password' => Hash::make($request->password)]
    ));

    // Se genera un token JWT para el usuario registrado
    $token = auth('api')->login($user);

    return response()->json([
      'message'      => 'Usuario registrado con éxito',
      'user'         => $user,
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => 86400
    ], 201);
  }

  /**
   * Envía un código de verificación al correo electrónico registrado del usuario.
   *
   * Se espera recibir en el request:
   * - user_id: El ID del usuario.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendEmailCode(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::findOrFail($request->user_id);

    // Generar un código de verificación de 6 dígitos
    $code = rand(100000, 999999);

    // Guardar el código de verificación de forma encriptada en el campo 'email_verification_code'
    // y resetear la verificación previa
    $user->email_verification_code = Hash::make($code);
    $user->email_verified_at = null;
    $user->save();

    // Enviar el correo electrónico con el código de verificación
    try {
      Mail::send('emails.verification_code', ['code' => $code], function ($message) use ($user) {
        $message->to($user->email);
        $message->subject('Código de verificación de correo electrónico');
      });
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Error enviando el correo de verificación: ' . $e->getMessage()
      ], 500);
    }

    return response()->json([
      'message' => 'Código de verificación enviado a tu correo electrónico.'
    ], 200);
  }

  /**
   * Verifica el código de verificación recibido por el usuario.
   *
   * Se espera recibir en el request:
   * - user_id: El ID del usuario.
   * - verification_code: El código que el usuario recibió por correo.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function verifyEmailCode(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id'           => 'required|exists:users,id',
      'verification_code' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::findOrFail($request->user_id);

    if (!$user->email_verification_code) {
      return response()->json([
        'error' => 'No hay un código de verificación pendiente para este usuario.'
      ], 422);
    }

    // Comparar el código ingresado con el almacenado (encriptado)
    if (!Hash::check($request->verification_code, $user->email_verification_code)) {
      return response()->json([
        'error' => 'Código de verificación inválido.'
      ], 422);
    }

    // Si el código es correcto, se elimina el código almacenado y se establece la fecha de verificación
    $user->email_verification_code = null;
    $user->email_verified_at = now();
    $user->save();

    return response()->json([
      'message' => 'Correo electrónico verificado correctamente.'
    ], 200);
  }

  /**
   * Actualiza el número de teléfono de un usuario, formateándolo a E.164 y enviando un código
   * de verificación por SMS.
   *
   * Se espera recibir en el request:
   * - user_id: el ID del usuario.
   * - phone: el número de teléfono sin código de nacionalidad.
   * - codigo_nacional: el código de nacionalidad (por ejemplo, "57" para Colombia).
   *
   * Ejemplo de formato final: +573057921778
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function addPhoneNumber(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id'         => 'required|exists:users,id',
      'phone'           => 'required|string',
      'codigo_nacional' => 'required|string'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::findOrFail($request->user_id);

    // Eliminar el signo "+" en caso de que venga incluido en el código de nacionalidad
    $codigoNacional = ltrim($request->codigo_nacional, '+');
    // Formatear el número concatenando "+" + código de nacionalidad + número de teléfono
    $formattedPhone = '+' . $codigoNacional . $request->phone;

    // Validar que el número cumpla con el formato E.164
    if (!preg_match('/^\+[1-9]\d{1,14}$/', $formattedPhone)) {
      return response()->json(['error' => 'El número de teléfono no cumple con el formato E.164'], 422);
    }

    // Actualizar el número de teléfono y resetear la verificación
    $user->phone = $formattedPhone;
    $user->phone_verified_at = null;
    // Generar código de verificación de 6 dígitos
    $code = rand(100000, 999999);
    // Almacenar el código de verificación de forma encriptada
    $user->phone_verification_code = Hash::make($code);
    $user->save();

    // Enviar SMS con el código de verificación usando la lógica existente
    try {
      $sid         = config('services.twilio.sid');
      $tokenTwilio = config('services.twilio.token');
      $from        = config('services.twilio.from');

      $client = new Client($sid, $tokenTwilio);
      $client->messages->create(
        $formattedPhone,
        [
          'from' => $from,
          'body' => "Tu código de verificación es: $code"
        ]
      );
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error enviando SMS: ' . $e->getMessage()], 500);
    }

    return response()->json(['message' => 'Número de teléfono actualizado y código de verificación enviado.'], 200);
  }

  /**
   * Verifica el número de teléfono comparando el código enviado por SMS con el que se tiene almacenado.
   *
   * Se espera recibir en el request:
   * - user_id: el ID del usuario.
   * - verification_code: el código que recibió el usuario.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function verifyPhoneNumber(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id'           => 'required|exists:users,id',
      'verification_code' => 'required|string'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::findOrFail($request->user_id);

    if (!$user->phone_verification_code) {
      return response()->json(['error' => 'No hay un código de verificación pendiente para este usuario.'], 422);
    }

    // Comparar el código ingresado con el código almacenado (encriptado)
    if (!Hash::check($request->verification_code, $user->phone_verification_code)) {
      return response()->json(['error' => 'Código de verificación inválido.'], 422);
    }

    // Actualizar el estado de verificación: borrar el código y establecer la fecha de verificación
    $user->phone_verification_code = null;
    $user->phone_verified_at = now();
    $user->save();

    return response()->json(['message' => 'Número de teléfono verificado correctamente.'], 200);
  }

  /* ====================================================
    CRUD Completo para la Gestión de Usuarios (sólo Administrador)
  ==================================================== */

  /**
   * Obtiene la lista de usuarios.
   *
   * @return \Illuminate\Http\JsonResponse
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
   * @return \Illuminate\Http\JsonResponse
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
   * @return \Illuminate\Http\JsonResponse
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

    // Se actualizan los campos del usuario
    $usuario->update($data);

    return response()->json(['mensaje' => 'Usuario actualizado', 'usuario' => $usuario], 200);
  }

  /**
   * Deshabilita un usuario (solo Administrador).
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy($id)
  {
    $this->checkRole('Administrador');
    $usuario = User::findOrFail($id);
    // Se deshabilita el usuario actualizando el campo "enabled"
    $usuario->update(['enabled' => 0]);
    return response()->json(['mensaje' => 'Usuario deshabilitado'], 200);
  }

  /* ====================================================
    Funcionalidad para Restablecimiento de Contraseña
    Soporta el envío del código por correo electrónico, SMS y WhatsApp.
  ==================================================== */

  /**
   * Envía el código de restablecimiento de contraseña por correo electrónico.
   *
   * Valida que el correo exista, genera un código de 6 dígitos y lo almacena en la tabla
   * password_reset_tokens. Luego, envía un correo con el código.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetEmail(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
      return response()->json(['error' => 'El correo electrónico ingresado no se encuentra registrado.'], 404);
    }

    // Genera un código de 6 dígitos en texto plano
    $code = rand(100000, 999999);
    // Encripta el código para almacenarlo de forma segura
    $hashedCode = Hash::make($code);

    // Almacena el código en la base de datos sin encriptar (para este método, se envía tal cual por email)
    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $request->email],
      [
        'token' => $hashedCode,
        'created_at' => Carbon::now()
      ]
    );

    // Enviar correo con el código de restablecimiento
    try {
      Mail::send('emails.password_code', ['code' => $code], function ($message) use ($request) {
        $message->to($request->email);
        $message->subject('Código de Restablecimiento de Contraseña');
      });

      return response()->json([
        'message' => 'Código de verificación enviado a tu correo.'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Error enviando el correo',
        'details' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Envía el código de restablecimiento de contraseña por SMS.
   *
   * Valida que el correo exista y que el usuario tenga un número de teléfono registrado.
   * Genera un código de 6 dígitos, lo encripta y lo almacena en la tabla password_reset_tokens,
   * y luego lo envía al teléfono del usuario utilizando Twilio.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetSMS(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user->phone) {
      return response()->json(['error' => 'El usuario no tiene un número de teléfono registrado.'], 404);
    }

    // Genera un código de 6 dígitos en texto plano
    $code = rand(100000, 999999);
    // Encripta el código para almacenarlo de forma segura
    $hashedCode = Hash::make($code);

    // Almacena o actualiza el código en la tabla password_reset_tokens
    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $request->email],
      [
        'token'      => $hashedCode,
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
   * Envía el código de restablecimiento de contraseña por WhatsApp.
   *
   * Valida que el correo exista, que el usuario tenga un número registrado y en formato E.164.
   * Genera un código de 6 dígitos, lo almacena en la tabla password_reset_tokens y lo envía al número
   * de WhatsApp del usuario utilizando la API de Twilio para WhatsApp.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetWhatsapp(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email'
    ]);

    $user = User::where('email', $request->email)->firstOrFail();

    if (!$user->phone) {
      return response()->json(['error' => 'El usuario no tiene un número de teléfono registrado.'], 404);
    }

    // Validar que el número esté en formato E.164
    if (!preg_match('/^\+[1-9]\d{1,14}$/', $user->phone)) {
      return response()->json([
        'error' => 'Número inválido',
        'message' => 'El número debe estar en formato E.164 (ej: +573001234567)'
      ], 422);
    }

    // Genera un código de 6 dígitos en texto plano
    $code = rand(100000, 999999);
    // Encripta el código para almacenarlo de forma segura
    $hashedCode = Hash::make($code);

    // Almacena el código en la tabla password_reset_tokens (en este método se guarda sin encriptar)
    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $request->email],
      [
        'token'      => $hashedCode,
        'created_at' => Carbon::now()
      ]
    );

    try {
      $sid = config('services.twilio.sid');
      $tokenTwilio = config('services.twilio.token');
      $from = config('services.twilio.whatsapp_from');

      $client = new Client($sid, $tokenTwilio);

      // Enviar mensaje vía WhatsApp
      $message = $client->messages->create(
        "whatsapp:{$user->phone}",
        [
          "from" => "whatsapp:" . $from,
          'body' => "Tu código de verificación para restablecer la contraseña es: $code"
        ]
      );

      return response()->json([
        'success' => true,
        'message' => 'Código enviado por WhatsApp',
        'whatsapp_sid' => $message->sid
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Error en envío por WhatsApp',
        'twilio_error' => $e->getMessage(),
        'debug' => [
          'number' => $user->phone
        ]
      ], 500);
    }
  }

  /**
   * Restablece la contraseña del usuario.
   *
   * Valida que el email, el código (enviado por correo, SMS o WhatsApp)
   * y la nueva contraseña sean correctos. Utiliza Hash::check para comparar
   * el código ingresado con el token almacenado (encriptado en caso de SMS).
   * Si la validación es exitosa, actualiza la contraseña del usuario (encriptándola)
   * y elimina el registro en password_reset_tokens.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function resetPassword(Request $request)
  {
    $request->validate([
      'email'    => 'required|email|exists:users,email',
      'token'    => 'required',
      'password' => 'required|string|min:6|confirmed'
    ]);

    try {
      // Buscar el registro en password_reset_tokens para el email
      $record = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

      if (!$record) {
        return response()->json(['error' => 'No se encontró un código para este correo.'], 404);
      }

      // Verificar que el código no haya expirado (60 minutos de validez)
      if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
        return response()->json(['error' => 'El código ha expirado.'], 422);
      }

      // Validar el código: para SMS y WhatsApp, el token se almacena encriptado; para correo, se puede almacenar en texto plano
      // Validar el código usando Hash::check para comparar con el token encriptado
      if (!Hash::check($request->token, $record->token)) {
        return response()->json(['error' => 'Código inválido.'], 422);
      }

      // Actualizar la contraseña del usuario (encriptándola)
      $user = User::where('email', $request->email)->firstOrFail();
      $user->password = Hash::make($request->password);

      if (!$user->save()) {
        throw new \Exception('Error al actualizar la contraseña.');
      }

      // Eliminar el registro del código de restablecimiento
      DB::table('password_reset_tokens')->where('email', $request->email)->delete();

      return response()->json(['mensaje' => 'Contraseña actualizada con éxito.'], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json(['error' => 'Usuario no encontrado.'], 404);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /* ====================================================
    Helper: Verificar Rol del Usuario Autenticado.
  ==================================================== */

  /**
   * Verifica que el usuario autenticado tenga el rol requerido.
   *
   * @param string $requiredRole
   * @return void
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
