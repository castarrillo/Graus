<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaloisController;

/*
|--------------------------------------------------------------------------
| API Routes for Galois Platform
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas públicas y protegidas (middleware auth:api) para
| el manejo de la plataforma Galois, incluyendo CRUD y consultas detalladas
| para cada entidad, así como funciones para obtener rankings.
|
*/

// ==============================
// Rutas Públicas (sin auth:api)
// ==============================
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/sendResetLink', [AuthController::class, 'sendResetLink']);
Route::post('auth/sendResetCode', [AuthController::class, 'sendResetCode']);
Route::post('auth/resetPasswordGalois', [AuthController::class, 'resetPasswordGalois']);

// ==============================
// Rutas Protegidas (con middleware auth:api)
// ==============================
Route::group(['middleware' => 'auth:api'], function () {

  // -- Autenticación --
  Route::get('auth/profile', [AuthController::class, 'profile']);
  Route::post('auth/logout', [AuthController::class, 'logout']);
  Route::post('auth/refresh', [AuthController::class, 'refresh']);

  // -- Usuarios (opcional, para gestión de usuarios) --
  Route::get('users', [AuthController::class, 'index']);
  Route::get('users/{id}', [AuthController::class, 'show']);
  Route::patch('users/{id}', [AuthController::class, 'update']);
  Route::delete('users/{id}', [AuthController::class, 'destroy']);

  // ===================================================
  // COLEGIOS (Solo para Administradores)
  // ===================================================
  Route::get('getColegios', [GaloisController::class, 'getColegios']);
  Route::get('detailColegio/{id}', [GaloisController::class, 'detailColegio']);
  Route::post('createColegio', [GaloisController::class, 'createColegio']);
  Route::patch('updateColegio/{id}', [GaloisController::class, 'updateColegio']);
  Route::delete('deleteColegio/{id}', [GaloisController::class, 'deleteColegio']);

  // ===================================================
  // ROLES (Solo para Administradores)
  // ===================================================
  Route::get('getRoles', [GaloisController::class, 'getRoles']);
  Route::get('detailRole/{id}', [GaloisController::class, 'detailRole']);
  Route::post('createRole', [GaloisController::class, 'createRole']);
  Route::patch('updateRole/{id}', [GaloisController::class, 'updateRole']);
  Route::delete('deleteRole/{id}', [GaloisController::class, 'deleteRole']);

  // ===================================================
  // CURSOS (Solo para Profesores)
  // ===================================================
  Route::get('getCursos', [GaloisController::class, 'getCursos']);
  Route::get('detailCurso/{id}', [GaloisController::class, 'detailCurso']);
  Route::post('createCurso', [GaloisController::class, 'createCurso']);
  Route::patch('updateCurso/{id}', [GaloisController::class, 'updateCurso']);
  Route::delete('deleteCurso/{id}', [GaloisController::class, 'deleteCurso']);

  // ===================================================
  // CURSO USUARIOS (Solo para Profesores)
  // ===================================================
  Route::get('getCursoUsuarios', [GaloisController::class, 'getCursoUsuarios']);
  Route::get('detailCursoUsuario/{id}', [GaloisController::class, 'detailCursoUsuario']);
  Route::post('createCursoUsuario', [GaloisController::class, 'createCursoUsuario']);
  Route::patch('updateCursoUsuario/{id}', [GaloisController::class, 'updateCursoUsuario']);
  Route::delete('deleteCursoUsuario/{id}', [GaloisController::class, 'deleteCursoUsuario']);

  // ===================================================
  // EVALUACIONES ALTERNATIVAS (Solo para Profesores)
  // ===================================================
  Route::get('getEvaluacionesAlternativas', [GaloisController::class, 'getEvaluacionesAlternativas']);
  Route::get('detailEvaluacionAlternativa/{id}', [GaloisController::class, 'detailEvaluacionAlternativa']);
  Route::post('createEvaluacionAlternativa', [GaloisController::class, 'createEvaluacionAlternativa']);
  Route::patch('updateEvaluacionAlternativa/{id}', [GaloisController::class, 'updateEvaluacionAlternativa']);
  Route::delete('deleteEvaluacionAlternativa/{id}', [GaloisController::class, 'deleteEvaluacionAlternativa']);

  // ===================================================
  // NOTIFICACIONES (Acceso libre o según lógica de negocio)
  // ===================================================
  Route::get('getNotificaciones', [GaloisController::class, 'getNotificaciones']);
  Route::get('detailNotificacion/{id}', [GaloisController::class, 'detailNotificacion']);
  Route::post('createNotificacion', [GaloisController::class, 'createNotificacion']);
  Route::patch('updateNotificacion/{id}', [GaloisController::class, 'updateNotificacion']);
  Route::delete('deleteNotificacion/{id}', [GaloisController::class, 'deleteNotificacion']);

  // ===================================================
  // RACHAS AMIGOS (Estudiantes retándose entre sí)
  // ===================================================
  Route::get('getRachaAmigos', [GaloisController::class, 'getRachaAmigos']);
  Route::get('detailRachaAmigos/{id}', [GaloisController::class, 'detailRachaAmigos']);
  Route::post('createRachaAmigos', [GaloisController::class, 'createRachaAmigos']);
  Route::patch('updateRachaAmigos/{id}', [GaloisController::class, 'updateRachaAmigos']);
  Route::delete('deleteRachaAmigos/{id}', [GaloisController::class, 'deleteRachaAmigos']);

  // ===================================================
  // USUARIO RECOMPENSAS (Solo para Profesores)
  // ===================================================
  Route::get('getUsuarioRecompensas', [GaloisController::class, 'getUsuarioRecompensas']);
  Route::get('detailUsuarioRecompensa/{id}', [GaloisController::class, 'detailUsuarioRecompensa']);
  Route::post('createUsuarioRecompensa', [GaloisController::class, 'createUsuarioRecompensa']);
  Route::patch('updateUsuarioRecompensa/{id}', [GaloisController::class, 'updateUsuarioRecompensa']);
  Route::delete('deleteUsuarioRecompensa/{id}', [GaloisController::class, 'deleteUsuarioRecompensa']);

  // ===================================================
  // USUARIO LECCIONES (Estudiantes)
  // ===================================================
  Route::get('getUsuarioLecciones', [GaloisController::class, 'getUsuarioLecciones']);
  Route::get('detailUsuarioLeccion/{id}', [GaloisController::class, 'detailUsuarioLeccion']);
  Route::post('createUsuarioLeccion', [GaloisController::class, 'createUsuarioLeccion']);
  Route::patch('updateUsuarioLeccion/{id}', [GaloisController::class, 'updateUsuarioLeccion']);
  Route::delete('deleteUsuarioLeccion/{id}', [GaloisController::class, 'deleteUsuarioLeccion']);

  // ===================================================
  // PUNTOS USUARIO (Según lógica de negocio)
  // ===================================================
  Route::get('getPuntosUsuarios', [GaloisController::class, 'getPuntosUsuarios']);
  Route::get('detailPuntosUsuario/{id}', [GaloisController::class, 'detailPuntosUsuario']);
  Route::post('createPuntosUsuario', [GaloisController::class, 'createPuntosUsuario']);
  Route::patch('updatePuntosUsuario/{id}', [GaloisController::class, 'updatePuntosUsuario']);
  Route::delete('deletePuntosUsuario/{id}', [GaloisController::class, 'deletePuntosUsuario']);

  // ===================================================
  // PAISES (Solo para Administradores)
  // ===================================================
  Route::get('getPaises', [GaloisController::class, 'getPaises']);
  Route::get('detailPais/{id}', [GaloisController::class, 'detailPais']);
  Route::post('createPais', [GaloisController::class, 'createPais']);
  Route::patch('updatePais/{id}', [GaloisController::class, 'updatePais']);
  Route::delete('deletePais/{id}', [GaloisController::class, 'deletePais']);

  // ===================================================
  // DEPARTAMENTOS (Solo para Administradores)
  // ===================================================
  Route::get('getDepartamentos', [GaloisController::class, 'getDepartamentos']);
  Route::get('detailDepartamento/{id}', [GaloisController::class, 'detailDepartamento']);
  Route::post('createDepartamento', [GaloisController::class, 'createDepartamento']);
  Route::patch('updateDepartamento/{id}', [GaloisController::class, 'updateDepartamento']);
  Route::delete('deleteDepartamento/{id}', [GaloisController::class, 'deleteDepartamento']);

  // ===================================================
  // CIUDADES (Solo para Administradores)
  // ===================================================
  Route::get('getCiudades', [GaloisController::class, 'getCiudades']);
  Route::get('detailCiudad/{id}', [GaloisController::class, 'detailCiudad']);
  Route::post('createCiudad', [GaloisController::class, 'createCiudad']);
  Route::patch('updateCiudad/{id}', [GaloisController::class, 'updateCiudad']);
  Route::delete('deleteCiudad/{id}', [GaloisController::class, 'deleteCiudad']);

  // ===================================================
  // RANKING DE PUNTOS
  // ===================================================
  Route::get('ranking/nacional', [GaloisController::class, 'rankingNacional']);
  Route::get('ranking/departamental/{departamentoId}', [GaloisController::class, 'rankingDepartamental']);
  Route::get('ranking/ciudad/{ciudadId}', [GaloisController::class, 'rankingCiudad']);
  Route::get('ranking/colegio/{colegioId}', [GaloisController::class, 'rankingColegio']);

  // ===================================================
  // ASIGNACIÓN DE ROLES
  // ===================================================
  Route::post('assignRole', [GaloisController::class, 'assignRole']);

  Route::post('sendTestSms', [AuthController::class, 'sendTestSms']);
});
