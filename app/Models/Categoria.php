<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla 'categoria'.
 *
 * @property int $id_categoria
 * @property string $nombre
 * @property int $id
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Gasto[] $gastos
 * @property-read int|null $gastos_count
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria whereIdCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categoria whereUpdatedAt($value)
 */
class Categoria extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'categoria'; 

    /**
     * Clave primaria de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $primaryKey = 'id_categoria';

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'id', 'color']; 

    /**
     * Obtiene el usuario asociado a la categoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id'); 
    }

    /**
     * Obtiene los gastos asociados a la categoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_categoria');
    }
}
