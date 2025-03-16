<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Modelo User
 *
 * Representa un usuario del sistema.
 *
 * Atributos:
 * - name: Nombre del usuario.
 * - email: Correo electrónico.
 * - password: Contraseña.
 * - role_id: Identificador del rol asignado.
 * - colegio_id: Identificador del colegio al que pertenece.
 * - enabled: Estado del usuario (activo o deshabilitado).
 * - nivel: Nivel del usuario (principiante, intermedio, avanzado).
 * - phone: Número de teléfono.
 * - date_of_birth: Fecha de nacimiento.
 * - grado: Grado o nivel educativo.
 *
 * Relaciones:
 * - role: El usuario pertenece a un rol.
 * - colegio: El usuario pertenece a un colegio.
 * - aprendizajeEstudiante: Registros de aprendizaje del usuario.
 * - cursoUsuarios: Asignaciones de cursos al usuario.
 * - notificaciones: Notificaciones recibidas.
 * - puntosUsuario: Puntos asignados al usuario.
 * - cursos: Cursos en los que el usuario está inscrito.
 * - recompensas: Recompensas obtenidas.
 * - usuarioLecciones: Registros de lecciones realizadas.
 *
 * Implementa JWTSubject para la autenticación con JSON Web Tokens.
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
  protected $table = 'users';

  protected $fillable = [
    'name',
    'email',
    'email_verification_code',
    'email_verified_at',
    'password',
    'role_id',
    'colegio_id',
    'enabled',
    'nivel',
    'phone',
    'phone_verification_code',
    'phone_verified_at',
    'date_of_birth',
    'grado',
  ];

  public $timestamps = true;

  protected $hidden = [
    'password',
    'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
  ];

  /**
   * Relación: El usuario pertenece a un rol.
   *
   * @return BelongsTo
   */
  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class, 'role_id');
  }

  /**
   * Relación: El usuario pertenece a un colegio.
   *
   * @return BelongsTo
   */
  public function colegio(): BelongsTo
  {
    return $this->belongsTo(Colegio::class, 'colegio_id');
  }

  /**
   * Relación: Registros de aprendizaje del usuario.
   *
   * @return HasMany
   */
  public function aprendizajeEstudiante(): HasMany
  {
    return $this->hasMany(AprendizajeEstudiante::class, 'user_id');
  }

  /**
   * Relación: Asignaciones de cursos del usuario.
   *
   * @return HasMany
   */
  public function cursoUsuarios(): HasMany
  {
    return $this->hasMany(CursoUsuario::class, 'user_id');
  }

  /**
   * Relación: Notificaciones recibidas por el usuario.
   *
   * @return HasMany
   */
  public function notificaciones(): HasMany
  {
    return $this->hasMany(Notificacion::class, 'user_id');
  }

  /**
   * Relación: Puntos asignados al usuario.
   *
   * @return HasMany
   */
  public function puntosUsuario(): HasMany
  {
    return $this->hasMany(PuntosUsuario::class, 'user_id');
  }

  /**
   * Relación: Cursos en los que está inscrito el usuario.
   *
   * @return BelongsToMany
   */
  public function cursos(): BelongsToMany
  {
    return $this->belongsToMany(Curso::class, 'curso_usuarios', 'user_id', 'course_id')
      ->withTimestamps();
  }

  /**
   * Relación: Recompensas obtenidas por el usuario.
   *
   * @return BelongsToMany
   */
  public function recompensas(): BelongsToMany
  {
    return $this->belongsToMany(Recompensa::class, 'usuario_recompensas', 'user_id', 'recompensa_id')
      ->withPivot('awarded_at')
      ->withTimestamps();
  }

  /**
   * Relación: Registros de lecciones realizadas por el usuario.
   *
   * @return HasMany
   */
  public function usuarioLecciones(): HasMany
  {
    return $this->hasMany(UsuarioLeccion::class, 'user_id');
  }

  /**
   * Obtiene el identificador para el JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Retorna reclamos personalizados para el JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }
}
