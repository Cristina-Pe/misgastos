<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{

/**
 * Prueba para verificar si la página de inicio se carga correctamente.
 *
 * @return void
 */
public function test_home_page_loads_correctly()
{
    // Realizar una solicitud GET a la ruta de inicio
    $response = $this->get('/');

    // Verificar que la respuesta tenga un estado HTTP 200 (OK)
    $response->assertStatus(200);
    // Verificar que la vista devuelta sea 'home'
    $response->assertViewIs('home');
}
}
