<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo PuntosUsuario
 *
 * Representa los puntos asignados a un usuario.
 *
 * Atributos:
 * - user_id: Identificador del usuario.
 * - puntos: Cantidad de puntos asignados.
 * - motivo: Razón o descripción para la asignación de puntos.
 *
 * Relaciones:
 * - user: La asignación de puntos pertenece a un usuario.
 *
 * @package App\Models
 */
class PuntosUsuario extends Model
{
  protected $table = 'puntos_usuario';

  protected $fillable = [
    'user_id',
    'puntos',
    'motivo',
  ];

  public $timestamps = true;

  /**
   * Relación: La asignación de puntos pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
