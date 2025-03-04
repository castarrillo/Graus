<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Colegio;
use App\Models\Curso;
use App\Models\CursoUsuario;
use App\Models\Departamento;
use App\Models\EvaluacionAlternativa;
use App\Models\Notificacion;
use App\Models\Pais;
use App\Models\PuntosUsuario;
use App\Models\RachaAmigos;
use App\Models\Role;
use App\Models\UsuarioLeccion;
use App\Models\UsuarioRecompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GrausController extends Controller
{
  /*====================================================================
    CRUD - COLEGIOS (Solo Administrador)
  ====================================================================*/

  /**
   * Obtener la lista de colegios.
   */
  public function getColegios()
  {
    try {
      $this->checkRole('Administrador');
      $colegios = Colegio::all();
      return response()->json($colegios, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo colegios: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un colegio por ID.
   */
  public function detailColegio($id)
  {
    try {
      $this->checkRole('Administrador');
      $colegio = Colegio::findOrFail($id);
      return response()->json($colegio, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle del colegio: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un nuevo colegio.
   */
  public function createColegio(Request $request)
  {
    try {
      $this->checkRole('Administrador');
      $request->validate([
        'name'      => 'required|string',
        'address'   => 'nullable|string',
        'ciudad_id' => 'required|exists:ciudades,id'
      ]);
      $colegio = Colegio::create($request->only('name', 'address', 'ciudad_id'));
      return response()->json(['message' => 'Colegio creado', 'colegio' => $colegio], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando colegio: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un colegio existente.
   */
  public function updateColegio(Request $request, $id)
  {
    try {
      $this->checkRole('Administrador');
      $colegio = Colegio::findOrFail($id);
      $data = $request->only('name', 'address', 'ciudad_id', 'enabled');

      // Realizar soft delete si 'enabled' == 0
      if (isset($data['enabled']) && $data['enabled'] == 0) {
        $colegio->update(['enabled' => 0]);
        return response()->json(['message' => 'Colegio deshabilitado (soft delete)'], 200);
      }

      $colegio->update($data);
      return response()->json(['message' => 'Colegio actualizado', 'colegio' => $colegio], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando colegio: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar (soft delete) un colegio.
   */
  public function deleteColegio($id)
  {
    try {
      $this->checkRole('Administrador');
      $colegio = Colegio::findOrFail($id);
      $colegio->update(['enabled' => 0]);
      return response()->json(['message' => 'Colegio eliminado (soft delete)'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando colegio: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - ROLES (Solo Administrador)
  ====================================================================*/

  /**
   * Obtener la lista de roles.
   */
  public function getRoles()
  {
    try {
      $this->checkRole('Administrador');
      $roles = Role::all();
      return response()->json($roles, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo roles: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un rol por ID.
   */
  public function detailRole($id)
  {
    try {
      $this->checkRole('Administrador');
      $role = Role::findOrFail($id);
      return response()->json($role, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle del rol: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un nuevo rol.
   */
  public function createRole(Request $request)
  {
    try {
      $this->checkRole('Administrador');
      $request->validate([
        'name' => 'required|string|unique:roles,name'
      ]);
      $role = Role::create($request->only('name'));
      return response()->json(['message' => 'Rol creado', 'role' => $role], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando rol: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un rol existente.
   */
  public function updateRole(Request $request, $id)
  {
    try {
      $this->checkRole('Administrador');
      $role = Role::findOrFail($id);
      $data = $request->only('name', 'enabled');
      if (isset($data['enabled']) && $data['enabled'] == 0) {
        $role->update(['enabled' => 0]);
        return response()->json(['message' => 'Rol deshabilitado (soft delete)'], 200);
      }
      $role->update($data);
      return response()->json(['message' => 'Rol actualizado', 'role' => $role], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando rol: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar (soft delete) un rol.
   */
  public function deleteRole($id)
  {
    try {
      $this->checkRole('Administrador');
      $role = Role::findOrFail($id);
      $role->update(['enabled' => 0]);
      return response()->json(['message' => 'Rol eliminado (soft delete)'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando rol: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - CURSOS (Solo Profesor)
  ====================================================================*/

  /**
   * Obtener la lista de cursos.
   */
  public function getCursos()
  {
    try {
      $this->checkRole('Profesor');
      $cursos = Curso::all();
      return response()->json($cursos, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo cursos: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un curso por ID.
   */
  public function detailCurso($id)
  {
    try {
      $this->checkRole('Profesor');
      $curso = Curso::findOrFail($id);
      return response()->json($curso, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle del curso: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un nuevo curso.
   */
  public function createCurso(Request $request)
  {
    try {
      $this->checkRole('Profesor');
      $request->validate([
        'name' => 'required|string',
        'description' => 'nullable|string',
        'professor_id' => 'required|exists:users,id',
      ]);
      $curso = Curso::create($request->only('name', 'description', 'professor_id'));
      return response()->json(['message' => 'Curso creado', 'curso' => $curso], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando curso: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un curso existente.
   */
  public function updateCurso(Request $request, $id)
  {
    try {
      $this->checkRole('Profesor');
      $curso = Curso::findOrFail($id);
      $data = $request->only('name', 'description', 'enabled');
      if (isset($data['enabled']) && $data['enabled'] == 0) {
        $curso->update(['enabled' => 0]);
        return response()->json(['message' => 'Curso deshabilitado (soft delete)'], 200);
      }
      $curso->update($data);
      return response()->json(['message' => 'Curso actualizado', 'curso' => $curso], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando curso: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar (soft delete) un curso.
   */
  public function deleteCurso($id)
  {
    try {
      $this->checkRole('Profesor');
      $curso = Curso::findOrFail($id);
      $curso->update(['enabled' => 0]);
      return response()->json(['message' => 'Curso eliminado (soft delete)'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando curso: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - CURSO USUARIOS (Solo Profesor)
  ====================================================================*/

  /**
   * Obtener la lista de curso-usuarios.
   */
  public function getCursoUsuarios()
  {
    try {
      $this->checkRole('Profesor');
      $cursoUsuarios = CursoUsuario::all();
      return response()->json($cursoUsuarios, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo curso usuarios: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un curso-usuario por ID.
   */
  public function detailCursoUsuario($id)
  {
    try {
      $this->checkRole('Profesor');
      $cursoUsuario = CursoUsuario::findOrFail($id);
      return response()->json($cursoUsuario, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de curso usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un nuevo curso-usuario.
   */
  public function createCursoUsuario(Request $request)
  {
    try {
      $this->checkRole('Profesor');
      $request->validate([
        'course_id' => 'required|exists:cursos,id',
        'user_id' => 'required|exists:users,id',
      ]);
      $cursoUsuario = CursoUsuario::create($request->only('course_id', 'user_id'));
      return response()->json(['message' => 'CursoUsuario creado', 'cursoUsuario' => $cursoUsuario], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando curso usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un curso-usuario.
   */
  public function updateCursoUsuario(Request $request, $id)
  {
    try {
      $this->checkRole('Profesor');
      $cursoUsuario = CursoUsuario::findOrFail($id);
      $data = $request->only('enabled');
      if (isset($data['enabled']) && $data['enabled'] == 0) {
        $cursoUsuario->update(['enabled' => 0]);
        return response()->json(['message' => 'CursoUsuario deshabilitado'], 200);
      }
      $cursoUsuario->update($data);
      return response()->json(['message' => 'CursoUsuario actualizado', 'cursoUsuario' => $cursoUsuario], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando curso usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar (soft delete) un curso-usuario.
   */
  public function deleteCursoUsuario($id)
  {
    try {
      $this->checkRole('Profesor');
      $cursoUsuario = CursoUsuario::findOrFail($id);
      $cursoUsuario->update(['enabled' => 0]);
      return response()->json(['message' => 'CursoUsuario eliminado'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando curso usuario: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - EVALUACIONES ALTERNATIVAS (Solo Profesor)
  ====================================================================*/

  /**
   * Obtener la lista de evaluaciones alternativas.
   */
  public function getEvaluacionesAlternativas()
  {
    try {
      $this->checkRole('Profesor');
      $evaluaciones = EvaluacionAlternativa::all();
      return response()->json($evaluaciones, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo evaluaciones alternativas: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de una evaluación alternativa por ID.
   */
  public function detailEvaluacionAlternativa($id)
  {
    try {
      $this->checkRole('Profesor');
      $evaluacion = EvaluacionAlternativa::findOrFail($id);
      return response()->json($evaluacion, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de evaluación alternativa: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear una nueva evaluación alternativa.
   */
  public function createEvaluacionAlternativa(Request $request)
  {
    try {
      $this->checkRole('Profesor');
      $request->validate([
        'user_id'         => 'required|exists:users,id',
        'leccion_id'      => 'required|exists:lecciones,id',
        'actividad_id'    => 'required|exists:actividades,id',
        'tipo_evaluacion' => 'required|in:texto,emocional,desempeño',
        'resultado'       => 'required|string',
      ]);
      $evaluacion = EvaluacionAlternativa::create($request->only('user_id', 'leccion_id', 'actividad_id', 'tipo_evaluacion', 'resultado'));
      return response()->json(['message' => 'EvaluacionAlternativa creada', 'evaluacion' => $evaluacion], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando evaluación alternativa: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar una evaluación alternativa.
   */
  public function updateEvaluacionAlternativa(Request $request, $id)
  {
    try {
      $this->checkRole('Profesor');
      $evaluacion = EvaluacionAlternativa::findOrFail($id);
      $evaluacion->update($request->only('tipo_evaluacion', 'resultado'));
      return response()->json(['message' => 'EvaluacionAlternativa actualizada', 'evaluacion' => $evaluacion], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando evaluación alternativa: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar una evaluación alternativa.
   */
  public function deleteEvaluacionAlternativa($id)
  {
    try {
      $this->checkRole('Profesor');
      $evaluacion = EvaluacionAlternativa::findOrFail($id);
      $evaluacion->delete();
      return response()->json(['message' => 'EvaluacionAlternativa eliminada'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando evaluación alternativa: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - NOTIFICACIONES (Acceso libre o según lógica de negocio)
  ====================================================================*/

  /**
   * Obtener la lista de notificaciones.
   */
  public function getNotificaciones()
  {
    try {
      $notificaciones = Notificacion::all();
      return response()->json($notificaciones, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo notificaciones: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de una notificación por ID.
   */
  public function detailNotificacion($id)
  {
    try {
      $notificacion = Notificacion::findOrFail($id);
      return response()->json($notificacion, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de notificación: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear una nueva notificación.
   */
  public function createNotificacion(Request $request)
  {
    try {
      $request->validate([
        'user_id' => 'required|exists:users,id',
        'tipo'    => 'required|in:sistema,recordatorio,premio',
        'mensaje' => 'required|string',
        'estado'  => 'required|boolean',
      ]);
      $notificacion = Notificacion::create($request->only('user_id', 'tipo', 'mensaje', 'estado'));
      return response()->json(['message' => 'Notificacion creada', 'notificacion' => $notificacion], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando notificación: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar una notificación.
   */
  public function updateNotificacion(Request $request, $id)
  {
    try {
      $notificacion = Notificacion::findOrFail($id);
      $notificacion->update($request->only('tipo', 'mensaje', 'estado'));
      return response()->json(['message' => 'Notificacion actualizada', 'notificacion' => $notificacion], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando notificación: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar una notificación.
   */
  public function deleteNotificacion($id)
  {
    try {
      $notificacion = Notificacion::findOrFail($id);
      $notificacion->delete();
      return response()->json(['message' => 'Notificacion eliminada'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando notificación: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - RACHAS AMIGOS (Estudiantes)
  ====================================================================*/

  /**
   * Obtener la lista de rachas de amigos.
   */
  public function getRachaAmigos()
  {
    try {
      $this->checkRole('Estudiante');
      $rachas = RachaAmigos::all();
      return response()->json($rachas, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo rachas de amigos: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de una racha de amigos por ID.
   */
  public function detailRachaAmigos($id)
  {
    try {
      $this->checkRole('Estudiante');
      $racha = RachaAmigos::findOrFail($id);
      return response()->json($racha, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de racha de amigos: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear una racha de amigos.
   */
  public function createRachaAmigos(Request $request)
  {
    try {
      $this->checkRole('Estudiante');
      $request->validate([
        'user1_id' => 'required|exists:users,id',
        'user2_id' => 'required|exists:users,id',
      ]);
      if ($request->user1_id == $request->user2_id) {
        return response()->json(['error' => 'No puedes crear una racha con el mismo usuario'], 422);
      }
      $racha = RachaAmigos::create([
        'user1_id' => $request->user1_id,
        'user2_id' => $request->user2_id,
        'streak_days' => 0,
        'last_challenge_at' => null,
      ]);
      return response()->json(['message' => 'Racha de amigos creada', 'racha' => $racha], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando racha de amigos: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar una racha de amigos.
   */
  public function updateRachaAmigos(Request $request, $id)
  {
    try {
      $this->checkRole('Estudiante');
      $racha = RachaAmigos::findOrFail($id);
      $data = $request->only('streak_days', 'last_challenge_at');
      $racha->update($data);
      return response()->json(['message' => 'Racha de amigos actualizada', 'racha' => $racha], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando racha de amigos: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar una racha de amigos.
   */
  public function deleteRachaAmigos($id)
  {
    try {
      $this->checkRole('Estudiante');
      $racha = RachaAmigos::findOrFail($id);
      $racha->delete();
      return response()->json(['message' => 'Racha de amigos eliminada'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando racha de amigos: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - USUARIO RECOMPENSAS (Solo Profesor)
  ====================================================================*/

  /**
   * Obtener la lista de usuario recompensas.
   */
  public function getUsuarioRecompensas()
  {
    try {
      $this->checkRole('Profesor');
      $usuarioRecompensas = UsuarioRecompensa::all();
      return response()->json($usuarioRecompensas, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo usuario recompensas: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de una usuario recompensa por ID.
   */
  public function detailUsuarioRecompensa($id)
  {
    try {
      $this->checkRole('Profesor');
      $usuarioRecompensa = UsuarioRecompensa::findOrFail($id);
      return response()->json($usuarioRecompensa, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de usuario recompensa: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear una usuario recompensa.
   */
  public function createUsuarioRecompensa(Request $request)
  {
    try {
      $this->checkRole('Profesor');
      $request->validate([
        'user_id'       => 'required|exists:users,id',
        'recompensa_id' => 'required|exists:recompensas,id',
      ]);
      $usuarioRecompensa = UsuarioRecompensa::create($request->only('user_id', 'recompensa_id'));
      return response()->json(['message' => 'UsuarioRecompensa creada', 'usuarioRecompensa' => $usuarioRecompensa], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando usuario recompensa: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar una usuario recompensa.
   */
  public function updateUsuarioRecompensa(Request $request, $id)
  {
    try {
      $this->checkRole('Profesor');
      $usuarioRecompensa = UsuarioRecompensa::findOrFail($id);
      $data = $request->only('awarded_at', 'enabled');
      if (isset($data['enabled']) && $data['enabled'] == 0) {
        $usuarioRecompensa->update(['enabled' => 0]);
        return response()->json(['message' => 'UsuarioRecompensa deshabilitada'], 200);
      }
      $usuarioRecompensa->update($data);
      return response()->json(['message' => 'UsuarioRecompensa actualizada', 'usuarioRecompensa' => $usuarioRecompensa], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando usuario recompensa: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar (soft delete) una usuario recompensa.
   */
  public function deleteUsuarioRecompensa($id)
  {
    try {
      $this->checkRole('Profesor');
      $usuarioRecompensa = UsuarioRecompensa::findOrFail($id);
      $usuarioRecompensa->update(['enabled' => 0]);
      return response()->json(['message' => 'UsuarioRecompensa eliminada (soft delete)'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando usuario recompensa: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - USUARIO LECCIONES (Estudiantes)
  ====================================================================*/

  /**
   * Obtener la lista de usuario lecciones.
   */
  public function getUsuarioLecciones()
  {
    try {
      $usuarioLecciones = UsuarioLeccion::all();
      return response()->json($usuarioLecciones, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo usuario lecciones: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de una usuario lección por ID.
   */
  public function detailUsuarioLeccion($id)
  {
    try {
      $usuarioLeccion = UsuarioLeccion::findOrFail($id);
      return response()->json($usuarioLeccion, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de usuario lección: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear una nueva usuario lección.
   */
  public function createUsuarioLeccion(Request $request)
  {
    try {
      $request->validate([
        'user_id'    => 'required|exists:users,id',
        'leccion_id' => 'required|exists:lecciones,id',
        'status'     => 'required|string',
      ]);
      $usuarioLeccion = UsuarioLeccion::create($request->only('user_id', 'leccion_id', 'status'));
      return response()->json(['message' => 'UsuarioLeccion creada', 'usuarioLeccion' => $usuarioLeccion], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando usuario lección: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar una usuario lección.
   */
  public function updateUsuarioLeccion(Request $request, $id)
  {
    try {
      $usuarioLeccion = UsuarioLeccion::findOrFail($id);
      $data = $request->only('status', 'score', 'attempts', 'enabled');
      if (isset($data['enabled']) && $data['enabled'] == 0) {
        $usuarioLeccion->update(['enabled' => 0]);
        return response()->json(['message' => 'UsuarioLeccion deshabilitada'], 200);
      }
      $usuarioLeccion->update($data);
      return response()->json(['message' => 'UsuarioLeccion actualizada', 'usuarioLeccion' => $usuarioLeccion], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando usuario lección: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar (soft delete) una usuario lección.
   */
  public function deleteUsuarioLeccion($id)
  {
    try {
      $usuarioLeccion = UsuarioLeccion::findOrFail($id);
      $usuarioLeccion->update(['enabled' => 0]);
      return response()->json(['message' => 'UsuarioLeccion eliminada (soft delete)'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando usuario lección: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - PUNTOS USUARIO (Según lógica de negocio)
  ====================================================================*/

  /**
   * Obtener la lista de puntos de usuario.
   */
  public function getPuntosUsuarios()
  {
    try {
      $puntosUsuarios = PuntosUsuario::all();
      return response()->json($puntosUsuarios, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo puntos de usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un registro de puntos de usuario por ID.
   */
  public function detailPuntosUsuario($id)
  {
    try {
      $puntosUsuario = PuntosUsuario::findOrFail($id);
      return response()->json($puntosUsuario, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de puntos de usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un registro de puntos para un usuario.
   */
  public function createPuntosUsuario(Request $request)
  {
    try {
      $request->validate([
        'user_id' => 'required|exists:users,id',
        'puntos'  => 'required|integer',
        'motivo'  => 'required|string',
      ]);
      $puntosUsuario = PuntosUsuario::create($request->only('user_id', 'puntos', 'motivo'));
      return response()->json(['message' => 'PuntosUsuario creado', 'puntosUsuario' => $puntosUsuario], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando puntos de usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un registro de puntos de usuario.
   */
  public function updatePuntosUsuario(Request $request, $id)
  {
    try {
      $puntosUsuario = PuntosUsuario::findOrFail($id);
      $puntosUsuario->update($request->only('puntos', 'motivo'));
      return response()->json(['message' => 'PuntosUsuario actualizado', 'puntosUsuario' => $puntosUsuario], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando puntos de usuario: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar un registro de puntos de usuario.
   */
  public function deletePuntosUsuario($id)
  {
    try {
      $puntosUsuario = PuntosUsuario::findOrFail($id);
      $puntosUsuario->delete();
      return response()->json(['message' => 'PuntosUsuario eliminado'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando puntos de usuario: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - PAISES (Solo Administrador)
  ====================================================================*/

  /**
   * Obtener la lista de países.
   */
  public function getPaises()
  {
    try {
      $this->checkRole('Administrador');
      $paises = Pais::all();
      return response()->json($paises, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo países: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un país por ID.
   */
  public function detailPais($id)
  {
    try {
      $this->checkRole('Administrador');
      $pais = Pais::findOrFail($id);
      return response()->json($pais, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle del país: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un nuevo país.
   */
  public function createPais(Request $request)
  {
    try {
      $this->checkRole('Administrador');
      $request->validate([
        'name' => 'required|string|unique:paises,name',
      ]);
      $pais = Pais::create($request->only('name'));
      return response()->json(['message' => 'País creado', 'pais' => $pais], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando país: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un país.
   */
  public function updatePais(Request $request, $id)
  {
    try {
      $this->checkRole('Administrador');
      $pais = Pais::findOrFail($id);
      $pais->update($request->only('name'));
      return response()->json(['message' => 'País actualizado', 'pais' => $pais], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando país: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar un país.
   */
  public function deletePais($id)
  {
    try {
      $this->checkRole('Administrador');
      $pais = Pais::findOrFail($id);
      $pais->delete();
      return response()->json(['message' => 'País eliminado'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando país: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - DEPARTAMENTOS (Solo Administrador)
  ====================================================================*/

  /**
   * Obtener la lista de departamentos.
   */
  public function getDepartamentos()
  {
    try {
      $this->checkRole('Administrador');
      $departamentos = Departamento::all();
      return response()->json($departamentos, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo departamentos: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de un departamento por ID.
   */
  public function detailDepartamento($id)
  {
    try {
      $this->checkRole('Administrador');
      $departamento = Departamento::findOrFail($id);
      return response()->json($departamento, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle del departamento: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear un nuevo departamento.
   */
  public function createDepartamento(Request $request)
  {
    try {
      $this->checkRole('Administrador');
      $request->validate([
        'pais_id' => 'required|exists:paises,id',
        'name'    => 'required|string',
      ]);
      $departamento = Departamento::create($request->only('pais_id', 'name'));
      return response()->json(['message' => 'Departamento creado', 'departamento' => $departamento], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando departamento: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar un departamento.
   */
  public function updateDepartamento(Request $request, $id)
  {
    try {
      $this->checkRole('Administrador');
      $departamento = Departamento::findOrFail($id);
      $departamento->update($request->only('pais_id', 'name'));
      return response()->json(['message' => 'Departamento actualizado', 'departamento' => $departamento], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando departamento: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar un departamento.
   */
  public function deleteDepartamento($id)
  {
    try {
      $this->checkRole('Administrador');
      $departamento = Departamento::findOrFail($id);
      $departamento->delete();
      return response()->json(['message' => 'Departamento eliminado'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando departamento: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    CRUD - CIUDADES (Solo Administrador)
  ====================================================================*/

  /**
   * Obtener la lista de ciudades.
   */
  public function getCiudades()
  {
    try {
      $this->checkRole('Administrador');
      $ciudades = Ciudad::all();
      return response()->json($ciudades, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo ciudades: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el detalle de una ciudad por ID.
   */
  public function detailCiudad($id)
  {
    try {
      $this->checkRole('Administrador');
      $ciudad = Ciudad::findOrFail($id);
      return response()->json($ciudad, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo detalle de ciudad: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Crear una nueva ciudad.
   */
  public function createCiudad(Request $request)
  {
    try {
      $this->checkRole('Administrador');
      $request->validate([
        'departamento_id' => 'required|exists:departamentos,id',
        'name'            => 'required|string',
      ]);
      $ciudad = Ciudad::create($request->only('departamento_id', 'name'));
      return response()->json(['message' => 'Ciudad creada', 'ciudad' => $ciudad], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error creando ciudad: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Actualizar una ciudad.
   */
  public function updateCiudad(Request $request, $id)
  {
    try {
      $this->checkRole('Administrador');
      $ciudad = Ciudad::findOrFail($id);
      $ciudad->update($request->only('departamento_id', 'name'));
      return response()->json(['message' => 'Ciudad actualizada', 'ciudad' => $ciudad], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error actualizando ciudad: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Eliminar una ciudad.
   */
  public function deleteCiudad($id)
  {
    try {
      $this->checkRole('Administrador');
      $ciudad = Ciudad::findOrFail($id);
      $ciudad->delete();
      return response()->json(['message' => 'Ciudad eliminada'], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error eliminando ciudad: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    RANKING DE PUNTOS
    Métodos para obtener el ranking:
      - Nacional
      - Departamental (según Colegio -> Ciudad -> Departamento)
      - Ciudad (según Colegio -> Ciudad)
      - Colegio
  ====================================================================*/

  /**
   * Obtener el ranking nacional de puntajes.
   */
  public function rankingNacional()
  {
    try {
      $ranking = DB::table('puntos_usuario')
        ->select('user_id', DB::raw('SUM(puntos) as total_points'))
        ->groupBy('user_id')
        ->orderByDesc('total_points')
        ->get();
      return response()->json($ranking, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo ranking nacional: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el ranking departamental de puntajes.
   */
  public function rankingDepartamental($departamentoId)
  {
    try {
      $ranking = DB::table('puntos_usuario as pu')
        ->join('users as u', 'pu.user_id', '=', 'u.id')
        ->join('colegios as c', 'u.colegio_id', '=', 'c.id')
        ->join('ciudades as ci', 'c.ciudad_id', '=', 'ci.id')
        ->where('ci.departamento_id', $departamentoId)
        ->select('u.id as user_id', DB::raw('SUM(pu.puntos) as total_points'))
        ->groupBy('u.id')
        ->orderByDesc('total_points')
        ->get();
      return response()->json($ranking, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo ranking departamental: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el ranking por ciudad de puntajes.
   */
  public function rankingCiudad($ciudadId)
  {
    try {
      $ranking = DB::table('puntos_usuario as pu')
        ->join('users as u', 'pu.user_id', '=', 'u.id')
        ->join('colegios as c', 'u.colegio_id', '=', 'c.id')
        ->where('c.ciudad_id', $ciudadId)
        ->select('u.id as user_id', DB::raw('SUM(pu.puntos) as total_points'))
        ->groupBy('u.id')
        ->orderByDesc('total_points')
        ->get();
      return response()->json($ranking, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo ranking por ciudad: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener el ranking por colegio de puntajes.
   */
  public function rankingColegio($colegioId)
  {
    try {
      $ranking = DB::table('puntos_usuario as pu')
        ->join('users as u', 'pu.user_id', '=', 'u.id')
        ->where('u.colegio_id', $colegioId)
        ->select('u.id as user_id', DB::raw('SUM(pu.puntos) as total_points'))
        ->groupBy('u.id')
        ->orderByDesc('total_points')
        ->get();
      return response()->json($ranking, 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error obteniendo ranking por colegio: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    ASIGNACIÓN DE ROLES
  ====================================================================*/

  /**
   * Asignar un rol a un usuario.
   */
  public function assignRole(Request $request)
  {
    try {
      $authUser = Auth::user();
      if (!$authUser) {
        return response()->json(['error' => 'No autenticado'], 401);
      }
      $request->validate([
        'target_user_id' => 'required|exists:users,id',
        'role_name'      => 'required|in:Administrador,Coordinador,Profesor,Estudiante',
      ]);
      $targetUser = \App\Models\User::findOrFail($request->target_user_id);
      $role = Role::where('name', $request->role_name)->first();
      if (!$role) {
        return response()->json(['error' => 'Rol inválido'], 422);
      }
      // Validar permisos para asignar roles
      if ($authUser->role->name === 'Administrador') {
        // Puede asignar cualquier rol.
      } elseif ($authUser->role->name === 'Coordinador') {
        if (!in_array($request->role_name, ['Profesor', 'Estudiante'])) {
          return response()->json(['error' => 'El Coordinador solo puede asignar Profesor o Estudiante'], 403);
        }
      } else {
        return response()->json(['error' => 'No tienes permisos para asignar roles'], 403);
      }
      $targetUser->role_id = $role->id;
      $targetUser->save();
      return response()->json([
        'message'   => 'Rol asignado correctamente',
        'usuario'   => $targetUser->name,
        'nuevo_rol' => $role->name,
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error asignando rol: ' . $e->getMessage()], 500);
    }
  }

  /*====================================================================
    HELPER: VERIFICAR ROL DEL USUARIO AUTENTICADO
  ====================================================================*/

  /**
   * Verifica que el usuario autenticado tenga el rol requerido.
   *
   * @param  string  $requiredRole
   */
  private function checkRole($requiredRole)
  {
    $user = Auth::user();
    if (!$user || !$user->role) {
      abort(401, 'No autenticado o sin rol');
    }
    if ($user->role->name !== $requiredRole) {
      abort(403, "No tienes el rol de $requiredRole");
    }
  }
}
