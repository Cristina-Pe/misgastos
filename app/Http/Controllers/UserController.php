<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


/**
 * Controlador para gestionar las operaciones relacionadas con los usuarios en el panel de administración.
 */
class UserController extends Controller
{
    /**
     * Muestra el formulario de edición de un usuario.
     *
     * @param  int  $id ID del usuario a editar
     * @return \Illuminate\View\View Vista del formulario de edición
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Elimina un usuario.
     *
     * @param  int  $id ID del usuario a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección a la vista del panel de administración con un mensaje de éxito
     */
    public function delete($id)
    {
        $user = User::find($id);

        // Agrega lógica para la eliminación (puedes utilizar soft delete, etc.)
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Usuario eliminado correctamente');
    }

    /**
     * Actualiza los datos de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request Datos del formulario de actualización
     * @param  int  $id ID del usuario a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección a la vista del usuario actualizado con un mensaje de éxito
     */
    public function update(Request $request, $id)
    {
        Log::info('Datos recibidos por el update del usuario:', $request->all());
        
        // Definir mensajes personalizados para las reglas de validación
        $messages = [
            'string' => 'El campo nombre debe ser una cadena de texto.',
            'max' => 'El campo :attribute no debe contener más de :max caracteres.',
            'email' => 'El campo email debe ser una dirección de correo electrónico válida.',
            'unique' => 'Este email ya está en uso.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
            'regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
        ];
    
        // Validar los datos del formulario de actualización con mensajes personalizados
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => [
                'nullable',
                'sometimes',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
        ], $messages);
    
        // Obtener el usuario que se va a actualizar
        $user = User::findOrFail($id);
    
        // Actualizar los campos del usuario si se proporcionaron en la solicitud
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
    
        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password')); // Se encripta la contraseña
        }
    
        // Guardar los cambios en la base de datos
        if ($user->save()) {
            // Redirigir a la vista de edición con un mensaje de éxito
            return redirect()->route('home', ['id' => $user->id])->with('success', 'Usuario actualizado correctamente');
        } else {
            // Redirigir a la vista de edición con un mensaje de error
            return redirect()->route('home', ['id' => $user->id])->with('error', 'Ha ocurrido un error al actualizar el usuario');
        }
    }
    



    /**
     * Muestra los detalles de un usuario.
     *
     * @param  int  $id ID del usuario a mostrar
     * @return \Illuminate\View\View Vista de los detalles del usuario
     */
    public function show($id)
    {
        $user = User::with(['cuentas', 'gastos', 'objetivosDeAhorro'])->find($id);
        
        return view('user.profile', compact('user'));
    }

    /**
     * Actualiza el rol de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request Datos de la solicitud
     * @param  int  $userId ID del usuario cuyo rol se actualizará
     * @return \Illuminate\Http\RedirectResponse Redirección al panel de administración con un mensaje de éxito
     */
    public function updateRole(Request $request, $userId)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($userId);
    
        // Determinar el nuevo rol del usuario
        if ($user->role === 'admin') {
            // Si el usuario es administrador, cambiarlo a regular
            $user->role = 'regular';
        } else {
            // Si el usuario es regular, cambiarlo a administrador
            $user->role = 'admin';
        }
    
        // Guardar los cambios en el rol del usuario
        $user->save();
    
        // Redireccionar de vuelta a la lista de usuarios con un mensaje de éxito
        return redirect()->route('admin.dashboard')->with('success', 'Rol de usuario actualizado correctamente');
    }

    /**
     * Elimina un usuario desde el panel de administración.
     *
     * @param  int  $userId ID del usuario a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección al panel de administración con un mensaje de éxito
     */
    public function deleteUser($userId)
    {
        // Buscar el usuario
        $user = User::findOrFail($userId);

        // Eliminar los gastos asociados al usuario
        $user->objetivoAhorro()->delete();
        $user->gastos()->delete();
        $user->categorias()->delete();

        // Eliminar las cuentas asociadas al usuario
        $user->cuentas()->delete();

        // Finalmente, eliminar el usuario
        $user->delete();

        // Redireccionar o devolver una respuesta
        return redirect()->route('admin.dashboard')->with('success', 'Usuario eliminado correctamente');
    }

    /**
     * Elimina un usuario desde el panel de administración (método alternativo).
     *
     * @param  int|null  $userId ID del usuario a eliminar
     * @return void
     */
    public function deleteUser2($userId = null)
    {
        // Buscar el usuario
        $user = User::findOrFail($userId);

        // Eliminar los gastos asociados al usuario
        $user->objetivoAhorro()->delete();
        $user->gastos()->delete();
        $user->categorias()->delete();

        // Eliminar las cuentas asociadas al usuario
        $user->cuentas()->delete();

        // Finalmente, eliminar el usuario
        $user->delete();

        // Redireccionar o devolver una respuesta (en este caso, no se redirecciona)
    }

    /**
     * Muestra el panel de administración con la lista de usuarios.
     *
     * @return \Illuminate\View\View Vista del panel de administración
     */
    public function dashboard()
    {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }

    /**
 * Cierra la sesión del usuario autenticado.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
}




}
