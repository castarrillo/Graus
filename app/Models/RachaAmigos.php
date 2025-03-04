<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo RachaAmigos
 *
 * Representa una racha de desafíos entre dos usuarios.
 *
 * Atributos:
 * - user1_id: Identificador del primer usuario.
 * - user2_id: Identificador del segundo usuario.
 * - streak_days: Número de días consecutivos en la racha.
 * - last_challenge_at: Fecha y hora del último desafío.
 * - enabled: Estado de la racha (activo o deshabilitado).
 *
 * Relaciones:
 * - userOne: Primer usuario en la racha.
 * - userTwo: Segundo usuario en la racha.
 *
 * @package App\Models
 */
class RachaAmigos extends Model
{
  protected $table = 'rachas_amigos';

  protected $fillable = [
    'user1_id',
    'user2_id',
    'streak_days',
    'last_challenge_at',
    'enabled',
  ];

  /**
   * Relación: Primer usuario en la racha de amigos.
   *
   * @return BelongsTo
   */
  public function userOne(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user1_id');
  }

  /**
   * Relación: Segundo usuario en la racha de amigos.
   *
   * @return BelongsTo
   */
  public function userTwo(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user2_id');
  }
}
