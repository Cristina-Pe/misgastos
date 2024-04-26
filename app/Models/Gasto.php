<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla 'gasto'.
 *
 * @property int $id_gasto
 * @property int $id
 * @property int $id_categoria
 * @property int $id_cuenta
 * @property string $fecha
 * @property string $descripcion
 * @property float $importe
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categoria $categoria
 * @property-read \App\Models\Cuenta $cuenta
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereIdCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereIdCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereIdGasto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereImporte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gasto whereUpdatedAt($value)
 */
class Gasto extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'gasto';

    /**
     * Clave primaria de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $primaryKey = 'id_gasto';

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array
     */
    protected $fillable = ['id', 'id_categoria', 'id_cuenta', 'fecha', 'descripcion', 'importe'];

    /**
     * Obtiene el usuario asociado al gasto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Obtiene la categoría asociada al gasto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    /**
     * Obtiene la cuenta asociada al gasto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta');
    }
}
