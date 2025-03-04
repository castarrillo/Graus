<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Colegio
 *
 * Representa un colegio que pertenece a una ciudad.
 *
 * Atributos:
 * - name: Nombre del colegio.
 * - address: Dirección del colegio.
 * - ciudad_id: Identificador de la ciudad donde se ubica el colegio.
 * - enabled: Estado del colegio (activo o deshabilitado).
 *
 * Relaciones:
 * - users: Un colegio tiene muchos usuarios (estudiantes).
 * - ciudad: Un colegio pertenece a una ciudad.
 *
 * @package App\Models
 */
class Colegio extends Model
{
  protected $table = 'colegios';

  protected $fillable = [
    'name',
    'address',
    'ciudad_id',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: Un colegio tiene muchos usuarios.
   *
   * @return HasMany
   */
  public function users(): HasMany
  {
    return $this->hasMany(User::class, 'colegio_id');
  }

  /**
   * Relación: Un colegio pertenece a una ciudad.
   *
   * @return BelongsTo
   */
  public function ciudad(): BelongsTo
  {
    return $this->belongsTo(Ciudad::class, 'ciudad_id');
  }
}
