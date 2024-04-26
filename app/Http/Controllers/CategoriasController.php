<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

/**
 * Controlador para la gestión de categorías.
 */
class CategoriasController extends Controller
{
    /**
     * Muestra la página principal con las categorías del usuario autenticado.
     *
     * @return \Illuminate\View\View Vista de la página principal con las categorías.
     */
    public function index()
    {
        $categorias = auth()->user()->categorias;
        return view('home', compact('categorias'));
    }

    /**
     * Almacena una nueva categoría proporcionada por el usuario.
     *
     * @param  \Illuminate\Http\Request  $request Datos de la solicitud HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'required|string|max:7', 
        ]);

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->color = $request->color;
        $categoria->id = auth()->id(); // Asigna el ID del usuario actual

        $categoria->save();

        return redirect()->route('home')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Elimina una categoría específica.
     *
     * @param  int  $id ID de la categoría a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal.
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
    
        // Verifica si hay gastos asociados a esta categoría
        if ($categoria->gastos()->exists()) {
            return redirect()->route('home')->with('error', 'No se puede eliminar la categoría porque está asociada a uno o más gastos.');
        }
    
        // Verifica si el usuario autenticado es el propietario de la categoría
        if ($categoria->id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'No tienes permiso para eliminar esta categoría.');
        }
    
        // Elimina la categoría
        $categoria->delete();
    
        return redirect()->route('home')->with('success', 'La categoría ha sido eliminada correctamente.');
    }
    
}
