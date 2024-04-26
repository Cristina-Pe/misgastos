<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cuenta;

/**
 * Controlador para la gestión de cuentas de usuario.
 */
class CuentasController extends Controller
{
    /**
     * Muestra la página principal con las cuentas del usuario autenticado.
     *
     * @return \Illuminate\View\View Vista de la página principal con las cuentas.
     */
    public function index()
    {
        $cuentas = auth()->user()->cuentas;
        return view('home', compact('cuentas'));
    }


    /**
     * Almacena una nueva cuenta proporcionada por el usuario.
     *
     * @param  \Illuminate\Http\Request  $request Datos de la solicitud HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_cuenta' => 'required|string|max:255',
            'objetivo_ahorro' => 'required|numeric',
        ]);

        $cuenta = new Cuenta();
        $cuenta->nombre_cuenta = $request->nombre_cuenta;
        $cuenta->objetivo_ahorro = $request->objetivo_ahorro;
        $cuenta->id= auth()->id(); // Asigna el ID del usuario actual

        $cuenta->save();

        return redirect()->route('home')->with('success', 'Cuenta creada exitosamente.');
    }

    /**
     * Elimina una cuenta específica.
     *
     * @param  int  $id ID de la cuenta a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal.
     */
    public function destroy($id)
    {
        $cuenta = Cuenta::findOrFail($id);
    
        // Verifica si el usuario autenticado es el propietario de la cuenta
        if ($cuenta->id!== auth()->id()) {
            return redirect()->route('home')->with('error', 'No tienes permiso para eliminar esta cuenta.');
        }
    
        // Elimina la cuenta
        $cuenta->delete();
    
        return redirect()->route('home')->with('success', 'La cuenta ha sido eliminada correctamente.');
    }


   

}
