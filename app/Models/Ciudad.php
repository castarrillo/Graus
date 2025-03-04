<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Ciudad
 *
 * Representa una ciudad dentro de un departamento.
 *
 * Atributos:
 * - departamento_id: Identificador del departamento al que pertenece la ciudad.
 * - name: Nombre de la ciudad.
 *
 * Relaciones:
 * - departamento: La ciudad pertenece a un departamento.
 * - colegios: Una ciudad puede tener muchos colegios.
 *
 * @package App\Models
 */
class Ciudad extends Model
{
  protected $table = 'ciudades';

  protected $fillable = [
    'departamento_id',
    'name',
  ];

  public $timestamps = true;

  /**
   * Relación: La ciudad pertenece a un departamento.
   *
   * @return BelongsTo
   */
  public function departamento(): BelongsTo
  {
    return $this->belongsTo(Departamento::class, 'departamento_id');
  }

  /**
   * Relación: Una ciudad tiene muchos colegios.
   *
   * @return HasMany
   */
  public function colegios(): HasMany
  {
    return $this->hasMany(Colegio::class, 'ciudad_id');
  }
}
