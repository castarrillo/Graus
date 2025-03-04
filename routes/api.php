<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GrausController;

/*
|--------------------------------------------------------------------------
| API Routes for Graus Platform
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas públicas y protegidas (middleware auth:api) para
| el manejo de la plataforma Graus, incluyendo CRUD y consultas detalladas
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
Route::post('auth/resetPassword', [AuthController::class, 'resetPassword']);

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
  Route::get('getColegios', [GrausController::class, 'getColegios']);
  Route::get('detailColegio/{id}', [GrausController::class, 'detailColegio']);
  Route::post('createColegio', [GrausController::class, 'createColegio']);
  Route::patch('updateColegio/{id}', [GrausController::class, 'updateColegio']);
  Route::delete('deleteColegio/{id}', [GrausController::class, 'deleteColegio']);

  // ===================================================
  // ROLES (Solo para Administradores)
  // ===================================================
  Route::get('getRoles', [GrausController::class, 'getRoles']);
  Route::get('detailRole/{id}', [GrausController::class, 'detailRole']);
  Route::post('createRole', [GrausController::class, 'createRole']);
  Route::patch('updateRole/{id}', [GrausController::class, 'updateRole']);
  Route::delete('deleteRole/{id}', [GrausController::class, 'deleteRole']);

  // ===================================================
  // CURSOS (Solo para Profesores)
  // ===================================================
  Route::get('getCursos', [GrausController::class, 'getCursos']);
  Route::get('detailCurso/{id}', [GrausController::class, 'detailCurso']);
  Route::post('createCurso', [GrausController::class, 'createCurso']);
  Route::patch('updateCurso/{id}', [GrausController::class, 'updateCurso']);
  Route::delete('deleteCurso/{id}', [GrausController::class, 'deleteCurso']);

  // ===================================================
  // CURSO USUARIOS (Solo para Profesores)
  // ===================================================
  Route::get('getCursoUsuarios', [GrausController::class, 'getCursoUsuarios']);
  Route::get('detailCursoUsuario/{id}', [GrausController::class, 'detailCursoUsuario']);
  Route::post('createCursoUsuario', [GrausController::class, 'createCursoUsuario']);
  Route::patch('updateCursoUsuario/{id}', [GrausController::class, 'updateCursoUsuario']);
  Route::delete('deleteCursoUsuario/{id}', [GrausController::class, 'deleteCursoUsuario']);

  // ===================================================
  // EVALUACIONES ALTERNATIVAS (Solo para Profesores)
  // ===================================================
  Route::get('getEvaluacionesAlternativas', [GrausController::class, 'getEvaluacionesAlternativas']);
  Route::get('detailEvaluacionAlternativa/{id}', [GrausController::class, 'detailEvaluacionAlternativa']);
  Route::post('createEvaluacionAlternativa', [GrausController::class, 'createEvaluacionAlternativa']);
  Route::patch('updateEvaluacionAlternativa/{id}', [GrausController::class, 'updateEvaluacionAlternativa']);
  Route::delete('deleteEvaluacionAlternativa/{id}', [GrausController::class, 'deleteEvaluacionAlternativa']);

  // ===================================================
  // NOTIFICACIONES (Acceso libre o según lógica de negocio)
  // ===================================================
  Route::get('getNotificaciones', [GrausController::class, 'getNotificaciones']);
  Route::get('detailNotificacion/{id}', [GrausController::class, 'detailNotificacion']);
  Route::post('createNotificacion', [GrausController::class, 'createNotificacion']);
  Route::patch('updateNotificacion/{id}', [GrausController::class, 'updateNotificacion']);
  Route::delete('deleteNotificacion/{id}', [GrausController::class, 'deleteNotificacion']);

  // ===================================================
  // RACHAS AMIGOS (Estudiantes retándose entre sí)
  // ===================================================
  Route::get('getRachaAmigos', [GrausController::class, 'getRachaAmigos']);
  Route::get('detailRachaAmigos/{id}', [GrausController::class, 'detailRachaAmigos']);
  Route::post('createRachaAmigos', [GrausController::class, 'createRachaAmigos']);
  Route::patch('updateRachaAmigos/{id}', [GrausController::class, 'updateRachaAmigos']);
  Route::delete('deleteRachaAmigos/{id}', [GrausController::class, 'deleteRachaAmigos']);

  // ===================================================
  // USUARIO RECOMPENSAS (Solo para Profesores)
  // ===================================================
  Route::get('getUsuarioRecompensas', [GrausController::class, 'getUsuarioRecompensas']);
  Route::get('detailUsuarioRecompensa/{id}', [GrausController::class, 'detailUsuarioRecompensa']);
  Route::post('createUsuarioRecompensa', [GrausController::class, 'createUsuarioRecompensa']);
  Route::patch('updateUsuarioRecompensa/{id}', [GrausController::class, 'updateUsuarioRecompensa']);
  Route::delete('deleteUsuarioRecompensa/{id}', [GrausController::class, 'deleteUsuarioRecompensa']);

  // ===================================================
  // USUARIO LECCIONES (Estudiantes)
  // ===================================================
  Route::get('getUsuarioLecciones', [GrausController::class, 'getUsuarioLecciones']);
  Route::get('detailUsuarioLeccion/{id}', [GrausController::class, 'detailUsuarioLeccion']);
  Route::post('createUsuarioLeccion', [GrausController::class, 'createUsuarioLeccion']);
  Route::patch('updateUsuarioLeccion/{id}', [GrausController::class, 'updateUsuarioLeccion']);
  Route::delete('deleteUsuarioLeccion/{id}', [GrausController::class, 'deleteUsuarioLeccion']);

  // ===================================================
  // PUNTOS USUARIO (Según lógica de negocio)
  // ===================================================
  Route::get('getPuntosUsuarios', [GrausController::class, 'getPuntosUsuarios']);
  Route::get('detailPuntosUsuario/{id}', [GrausController::class, 'detailPuntosUsuario']);
  Route::post('createPuntosUsuario', [GrausController::class, 'createPuntosUsuario']);
  Route::patch('updatePuntosUsuario/{id}', [GrausController::class, 'updatePuntosUsuario']);
  Route::delete('deletePuntosUsuario/{id}', [GrausController::class, 'deletePuntosUsuario']);

  // ===================================================
  // PAISES (Solo para Administradores)
  // ===================================================
  Route::get('getPaises', [GrausController::class, 'getPaises']);
  Route::get('detailPais/{id}', [GrausController::class, 'detailPais']);
  Route::post('createPais', [GrausController::class, 'createPais']);
  Route::patch('updatePais/{id}', [GrausController::class, 'updatePais']);
  Route::delete('deletePais/{id}', [GrausController::class, 'deletePais']);

  // ===================================================
  // DEPARTAMENTOS (Solo para Administradores)
  // ===================================================
  Route::get('getDepartamentos', [GrausController::class, 'getDepartamentos']);
  Route::get('detailDepartamento/{id}', [GrausController::class, 'detailDepartamento']);
  Route::post('createDepartamento', [GrausController::class, 'createDepartamento']);
  Route::patch('updateDepartamento/{id}', [GrausController::class, 'updateDepartamento']);
  Route::delete('deleteDepartamento/{id}', [GrausController::class, 'deleteDepartamento']);

  // ===================================================
  // CIUDADES (Solo para Administradores)
  // ===================================================
  Route::get('getCiudades', [GrausController::class, 'getCiudades']);
  Route::get('detailCiudad/{id}', [GrausController::class, 'detailCiudad']);
  Route::post('createCiudad', [GrausController::class, 'createCiudad']);
  Route::patch('updateCiudad/{id}', [GrausController::class, 'updateCiudad']);
  Route::delete('deleteCiudad/{id}', [GrausController::class, 'deleteCiudad']);

  // ===================================================
  // RANKING DE PUNTOS
  // ===================================================
  Route::get('ranking/nacional', [GrausController::class, 'rankingNacional']);
  Route::get('ranking/departamental/{departamentoId}', [GrausController::class, 'rankingDepartamental']);
  Route::get('ranking/ciudad/{ciudadId}', [GrausController::class, 'rankingCiudad']);
  Route::get('ranking/colegio/{colegioId}', [GrausController::class, 'rankingColegio']);

  // ===================================================
  // ASIGNACIÓN DE ROLES
  // ===================================================
  Route::post('assignRole', [GrausController::class, 'assignRole']);

  Route::post('sendTestSms', [AuthController::class, 'sendTestSms']);
});
