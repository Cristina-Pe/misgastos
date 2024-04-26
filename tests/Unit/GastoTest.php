<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Gasto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GastoTest extends TestCase
{


    /**
     * Prueba para verificar si se puede crear un gasto.
     *
     * @return void
     */
    public function test_can_create_gasto()
    {
        // Datos del gasto de prueba
        $gastoData = [
            'descripcion' => 'Compra de alimentos',
            'fecha' => now(),
            'importe' => 50.00,
            'id_categoria' => 2,
            'id_cuenta' => 1,
            'id' => 1,
        ];

        // Crear un gasto utilizando los datos proporcionados
        $gasto = Gasto::create($gastoData);

        // Verificar que el gasto se haya creado correctamente
        $this->assertInstanceOf(Gasto::class, $gasto);
        $this->assertEquals($gastoData['descripcion'], $gasto->descripcion);
        $this->assertEquals($gastoData['fecha'], $gasto->fecha);
        $this->assertEquals($gastoData['importe'], $gasto->importe);
        $this->assertEquals($gastoData['id_categoria'], $gasto->id_categoria);
        $this->assertEquals($gastoData['id_cuenta'], $gasto->id_cuenta);
    }

    /**
     * Prueba para verificar si se puede eliminar un gasto.
     *
     * @return void
     */
    public function test_can_delete_gasto()
    {
        // Crear un gasto de prueba en la base de datos
        $gastoData = [
            'descripcion' => 'Compra de alimentos',
            'fecha' => now(),
            'importe' => 22,
            'id_categoria' => 2,
            'id_cuenta' => 1,
            'id' => 1,
        ];

        // Crear un gasto utilizando los datos proporcionados
        $gasto = Gasto::create($gastoData);

        // Hacer una solicitud para eliminar la categoría
        $response = $this->delete(route('gastos.destroy', $gasto->id_gasto));

        // Verificar que la categoría ha sido eliminada correctamente
       $response->assertStatus(302); // Verificar que se redirige después de la eliminación
        $this->assertDatabaseMissing('categoria', ['id' => $gasto->id_gasto]);
    }
}
