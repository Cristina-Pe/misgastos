<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTestUnit extends TestCase
{


    /**
     * Prueba para verificar si un usuario puede acceder a la página de inicio.
     *
     * @return void
     */
    public function test_user_can_access_home_page()
    {
        // Crear un usuario de prueba en la base de datos
        $user = User::factory()->create();

        // Hacer una solicitud a la página de inicio como el usuario autenticado
        $response = $this->actingAs($user)->get('/home');

        // Verificar que la respuesta tenga un código de estado 200 (éxito)
        $response->assertStatus(200);
        
        // Verificar que la vista devuelta sea 'home'
        $response->assertViewIs('home');
    }
}
