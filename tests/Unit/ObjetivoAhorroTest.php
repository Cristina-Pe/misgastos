<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ObjetivoAhorro;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjetivoAhorroTest extends TestCase
{
    

    /**
     * Prueba para verificar si se puede crear un objetivo de ahorro.
     *
     * @return void
     */
    public function test_can_create_objetivo_ahorro()
    {
        // Datos del objetivo de ahorro
        $objetivoData = [
            'id_cuenta' => 3,
            'objetivo' => 1000,
        ];

        // Crear un objetivo de ahorro en la base de datos
        $objetivo = ObjetivoAhorro::create($objetivoData);

        // Verificar que se haya creado correctamente el objetivo de ahorro
        $this->assertInstanceOf(ObjetivoAhorro::class, $objetivo);
        $this->assertEquals($objetivoData['id_cuenta'], $objetivo->id_cuenta);
        $this->assertEquals($objetivoData['objetivo'], $objetivo->objetivo);
    }
}
