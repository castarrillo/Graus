<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Role
 *
 * Representa un rol asignado a los usuarios del sistema.
 *
 * Atributos:
 * - name: Nombre del rol.
 * - enabled: Estado del rol (activo o deshabilitado).
 *
 * Relaciones:
 * - users: Usuarios que poseen este rol.
 *
 * @package App\Models
 */
class Role extends Model
{
  protected $table = 'roles';

  protected $fillable = [
    'name',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: Usuarios que tienen asignado este rol.
   *
   * @return HasMany
   */
  public function users(): HasMany
  {
    return $this->hasMany(User::class, 'role_id');
  }
}
