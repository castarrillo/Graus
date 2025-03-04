<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo UsuarioRecompensa
 *
 * Representa la relación entre un usuario y una recompensa obtenida.
 *
 * Atributos:
 * - user_id: Identificador del usuario.
 * - recompensa_id: Identificador de la recompensa.
 * - awarded_at: Fecha y hora en que se otorgó la recompensa.
 *
 * Relaciones:
 * - user: El registro pertenece a un usuario.
 * - recompensa: El registro pertenece a una recompensa.
 *
 * @package App\Models
 */
class UsuarioRecompensa extends Model
{
  protected $table = 'usuario_recompensas';

  protected $fillable = [
    'user_id',
    'recompensa_id',
    'awarded_at',
  ];

  public $timestamps = true;

  /**
   * Relación: El registro pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  /**
   * Relación: El registro pertenece a una recompensa.
   *
   * @return BelongsTo
   */
  public function recompensa(): BelongsTo
  {
    return $this->belongsTo(Recompensa::class, 'recompensa_id');
  }
}
