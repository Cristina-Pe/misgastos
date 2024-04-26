<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla 'Cuenta'.
 *
 * @property int $id_cuenta
 * @property int $id
 * @property string $nombre_cuenta
 * @property float $objetivo_ahorro
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gasto[] $gastos
 * @property-read int|null $gastos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ObjetivoAhorro[] $objetivosAhorro
 * @property-read int|null $objetivos_ahorro_count
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta whereIdCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta whereNombreCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta whereObjetivoAhorro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cuenta whereUpdatedAt($value)
 */
class Cuenta extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'Cuenta';

    /**
     * Clave primaria de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $primaryKey = 'id_cuenta';

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array
     */
    protected $fillable = ['id', 'nombre_cuenta', 'objetivo_ahorro'];

    /**
     * Obtiene el usuario asociado a la cuenta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Obtiene los objetivos de ahorro asociados a la cuenta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function objetivosAhorro()
    {
        return $this->hasMany(ObjetivoAhorro::class, 'id_cuenta');
    }

    /**
     * Obtiene los gastos asociados a la cuenta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_cuenta');
    }


}

