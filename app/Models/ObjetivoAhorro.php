<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla 'ObjetivoAhorro'.
 *
 * @property int $id_objetivo
 * @property int $id_cuenta
 * @property float $objetivo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cuenta $cuenta
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro whereIdCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro whereIdObjetivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro whereObjetivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjetivoAhorro whereUpdatedAt($value)
 */
class ObjetivoAhorro extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'ObjetivoAhorro';

    /**
     * Clave primaria de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $primaryKey = 'id_objetivo';

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array
     */
    protected $fillable = ['id_cuenta', 'objetivo'];

    /**
     * Obtiene la cuenta asociada al objetivo de ahorro.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta');
    }
}
