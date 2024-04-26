<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriaTest extends TestCase
{
 

    /**
     * Prueba para verificar si se puede crear una categoría.
     *
     * @return void
     */
    public function test_can_create_categoria()
    {
        // Datos de la categoría de prueba
        $categoriaData = [
            'nombre' => 'Test Categoria',
            'color' => '#FF0000',
            'id' => 1,
        ];

        // Crear una categoría utilizando los datos proporcionados
        $categoria = Categoria::create($categoriaData);

        // Verificar que la categoría se haya creado correctamente
        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals($categoriaData['nombre'], $categoria->nombre);
        $this->assertEquals($categoriaData['color'], $categoria->color);
        $this->assertEquals($categoriaData['id'], $categoria->id);
    }

 /**
     * Prueba para verificar si se puede eliminar una categoría.
     *
     * @return void
     */
    public function test_can_delete_categoria()
    {
        // Crear una categoría de prueba en la base de datos
        $categoria = Categoria::create([
            'nombre' => 'Categoría de prueba',
            'color' => '#00FF00',
            'id' => 1,
        ]);

        // Hacer una solicitud para eliminar la categoría
        $response = $this->delete(route('categorias.destroy', $categoria->id_categoria));

        // Verificar que la categoría ha sido eliminada correctamente
       $response->assertStatus(302); // Verificar que se redirige después de la eliminación
        $this->assertDatabaseMissing('categoria', ['id' => $categoria->id_categoria]);
    }
}