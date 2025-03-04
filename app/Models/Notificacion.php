<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Notificacion
 *
 * Representa una notificación enviada a un usuario.
 *
 * Atributos:
 * - user_id: Identificador del usuario receptor.
 * - tipo: Tipo de notificación (sistema, recordatorio, premio).
 * - mensaje: Contenido de la notificación.
 * - estado: Estado de la notificación (0 = no leída, 1 = leída).
 * - fecha_envio: Fecha y hora en que se envió la notificación.
 *
 * Relaciones:
 * - user: La notificación pertenece a un usuario.
 *
 * @package App\Models
 */
class Notificacion extends Model
{
  protected $table = 'notificaciones';

  protected $fillable = [
    'user_id',
    'tipo',
    'mensaje',
    'estado',
    'fecha_envio',
  ];

  public $timestamps = false;

  /**
   * Relación: La notificación pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
