<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo Recompensa
 *
 * Representa una recompensa que los usuarios pueden obtener.
 *
 * Atributos:
 * - nombre: Nombre de la recompensa.
 * - descripcion: Descripción de la recompensa.
 * - puntos_requeridos: Puntos necesarios para obtener la recompensa.
 * - estado: Estado de la recompensa (activo o deshabilitado).
 *
 * Relaciones:
 * - users: Usuarios que han obtenido esta recompensa.
 *
 * @package App\Models
 */
class Recompensa extends Model
{
  protected $table = 'recompensas';

  protected $fillable = [
    'nombre',
    'descripcion',
    'puntos_requeridos',
    'estado',
  ];

  public $timestamps = true;

  /**
   * Relación: Usuarios que han obtenido esta recompensa.
   *
   * @return BelongsToMany
   */
  public function users(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'usuario_recompensas', 'recompensa_id', 'user_id')
      ->withPivot('awarded_at')
      ->withTimestamps();
  }
}
