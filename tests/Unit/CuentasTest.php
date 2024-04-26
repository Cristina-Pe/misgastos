<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cuenta;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CuentaTest extends TestCase
{


    /**
     * Prueba para verificar si se puede crear una cuenta.
     *
     * @return void
     */
    public function test_can_create_cuenta()
    {
        // Datos de la cuenta de prueba
        $cuentaData = [
            'nombre_cuenta' => 'Cuenta de Ahorros',
            'objetivo_ahorro' => 5000,
            'id' => 1,
        ];

        // Crear una cuenta utilizando los datos proporcionados
        $cuenta = Cuenta::create($cuentaData);

        // Verificar que la cuenta se haya creado correctamente
        $this->assertInstanceOf(Cuenta::class, $cuenta);
        $this->assertEquals($cuentaData['nombre_cuenta'], $cuenta->nombre_cuenta);
        $this->assertEquals($cuentaData['objetivo_ahorro'], $cuenta->objetivo_ahorro);
        $this->assertEquals($cuentaData['id'], $cuenta->id);
    }
}
