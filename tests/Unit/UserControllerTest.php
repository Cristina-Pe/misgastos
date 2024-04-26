<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{


    /**
 * Prueba para verificar si se puede crear un usuario.
 *
 * @return void
 */
public function test_can_create_user()
{
    // Datos del usuario de prueba
    $userData = [
        'name' => 'Test User',
        'email' => 'test76@example.com',
        'password' => bcrypt('password'), // Se encripta la contraseña
        'role' => 'regular',
    ];

    // Crear un nuevo usuario en la base de datos
    $user = User::create($userData);

    // Verificar que se haya creado correctamente el usuario
    $this->assertInstanceOf(User::class, $user);
    $this->assertEquals($userData['name'], $user->name);
    $this->assertEquals($userData['email'], $user->email);
    // Verificar que la contraseña coincida después de ser encriptada
    $this->assertTrue(password_verify('password', $user->password));
    $this->assertEquals($userData['role'], $user->role);
}

    /**
     * Prueba para verificar si se puede editar un usuario.
     *
     * @return void
     */
    public function test_can_edit_user()
    {
        // Datos del usuario de prueba
        $userData = [
            'name' => 'Test User',
            'email' => 'test77@example.com', // Correo electrónico válido
            'password' => bcrypt('password'), // Se encripta la contraseña
            'role' => 'regular',
        ];
    
        // Crear un nuevo usuario en la base de datos
        $user = User::create($userData);
    
        // Nombre actualizado para el usuario
        $updatedName = 'Updated Name';
    
        // Hacer una solicitud para editar el usuario
        $response = $this->put("/user/{$user->id}", [
            'name' => $updatedName,
            'email' => 'test77@example.com', // Correo electrónico válido
        ]);
    
 
        // Recuperar el usuario actualizado de la base de datos
        $updatedUser = User::find($user->id);
    
        // Verificar que el nombre del usuario se ha actualizado correctamente
        $this->assertEquals($updatedName, $updatedUser->name);
    }
    

    /**
     * Prueba para verificar si se puede eliminar un usuario.
     *
     * @return void
     */
    public function test_can_delete_user()
    {
        // Crear un usuario de prueba en la base de datos
       // Datos del usuario de prueba
    $userData = [
        'name' => 'Test User',
        'email' => 'test77@example.com',
        'password' => bcrypt('password'), // Se encripta la contraseña
        'role' => 'regular',
    ];

    // Crear un nuevo usuario en la base de datos
    $user = User::create($userData);

        // Hacer una solicitud para eliminar el usuario
        $response = $this->delete("/admin/delete-user/{$user->id}");

        // Verificar que la solicitud se redirige al panel de administración
        $response->assertRedirect('/admin/dashboard');
        // Verificar que el usuario ha sido eliminado correctamente de la base de datos
        $this->assertNull(User::find($user->id));
    }

}
