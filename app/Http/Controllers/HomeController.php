<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Gasto;

/**
 * Controlador para gestionar la vista principal de la aplicación.
 */
class HomeController extends Controller
{
    /**
     * Crea una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        // Aplica el middleware de autenticación a todas las rutas del controlador
        $this->middleware('auth');
    }

    /**
     * Muestra la home de la aplicación.
     *
     * @return \Illuminate\Contracts\Support\Renderable Vista de la home.
     */
    public function index()
    {
        // Obtiene el usuario autenticado
        $user = Auth::user();

        // Verifica si el usuario está autenticado
        if (!$user) {
            // Si no está autenticado, devuelve un error 403 (Prohibido)
            abort(403); 
        }

        // Obtiene todas las cuentas del usuario
        $cuentas = $user->cuentas;

        // Obtiene el ID de la cuenta activa del usuario
        $cuentaActivaId = $user->cuenta_activa;

        // Obtiene las categorías asociadas al usuario (comunes para todas las cuentas)
        $categorias = auth()->user()->categorias;

        // Obtiene los gastos asociados a la cuenta activa del usuario
        $gastos = Gasto::where('id_cuenta', $cuentaActivaId)->with('categoria')->get();

        // Carga las relaciones necesarias
        $gastos->load('categoria');

        // Define los colores disponibles
        $colores = ['red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink', 'teal', 'cyan', 'lime', 'brown', 'gray'];

        // Devuelve la vista con los datos necesarios
        return view('home', compact('user', 'categorias', 'gastos', 'cuentas', 'colores'));
    }
}
