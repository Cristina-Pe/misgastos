<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto;
use App\Models\Cuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para la gestión de gastos de usuario.
 */
class GastosController extends Controller
{
    /**
     * Muestra la página principal con los gastos del usuario autenticado.
     *
     * @return \Illuminate\View\View Vista de la página principal con los gastos.
     */
    public function index()
    {
        $gastos = auth()->user()->gastos;
        return view('home', compact('gastos'));
    }

    /**
     * Muestra un gasto específico.
     *
     * @param  int  $id ID del gasto.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del gasto.
     */
    public function show($id)
    {
        $gasto = Gasto::findOrFail($id);
        return response()->json($gasto);
    }
 
    /**
     * Elimina un gasto específico.
     *
     * @param  int  $id ID del gasto a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON indicando el éxito de la eliminación.
     */
    public function destroy($id)
    {
        $gasto = Gasto::findOrFail($id);

        // Verifica si el usuario autenticado es el propietario del gasto
        if ($gasto->id!== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para eliminar este gasto.'], 403);
        }

        // Elimina el gasto
        $gasto->delete();

        return response()->json(['success' => true, 'message' => 'El gasto ha sido eliminado correctamente.'], 200);
    }


    /**
     * Actualiza un gasto existente con los datos proporcionados por el usuario.
     *
     * @param  \Illuminate\Http\Request  $request Datos de la solicitud HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal.
     */
    public function update(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
            'importe' => 'required|numeric',
            'categoria' => 'required|exists:categoria,id_categoria',
            // Puedes agregar más reglas de validación según sea necesario
        ]);
    
        // Encuentra el gasto a actualizar
        $gasto = Gasto::findOrFail($request->gasto_id);
    
        // Actualiza los datos del gasto
        $gasto->descripcion = $request->descripcion;
        $gasto->fecha = $request->fecha;
        $gasto->importe = $request->importe;
        $gasto->id_categoria = $request->categoria;
        // Actualiza cualquier otro campo según sea necesario
    
        // Guarda los cambios en la base de datos
        $gasto->save();
        return redirect('/home')->with('success', 'El gasto ha sido modificado correctamente.');
    }

    /**
     * Almacena un nuevo gasto o actualiza uno existente con los datos proporcionados por el usuario.
     *
     * @param  \Illuminate\Http\Request  $request Datos de la solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON indicando el éxito de la operación.
     */
    public function storeOrUpdate(Request $request)
    {
        Log::info('Datos recibidos en el controlador:', $request->all());
    
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|max:100',
            'fecha' => 'required|date',
            'importe' => 'required|numeric',
            'categoria' => 'required|exists:categoria,id_categoria',
            'cuenta' => 'required|exists:cuenta,id_cuenta',
        ]);

         // Verificar si hay errores de validación
    if ($validator->fails()) {
        // Si hay errores de validación, retornar los mensajes de error
        return response()->json($validator->errors(), 422);

    }
    
        // Crear un nuevo gasto con los datos proporcionados
        $gasto = new Gasto();
        $gasto->id = auth()->id();
        $gasto->descripcion = $request->descripcion;
        $gasto->fecha = $request->fecha;
        $gasto->importe = $request->importe;
        $gasto->id_categoria = $request->categoria;
        $gasto->id_cuenta = $request->cuenta;
    
        // Guardar el nuevo gasto en la base de datos
        if (!$gasto->save()) {
            // Si no se pudo guardar el gasto, retornar un mensaje de error
            return response()->json(['success' => false, 'message' => 'Error al crear el gasto.'], 400);
        }
        
              // Retornar una respuesta JSON indicando el éxito de la operación
        return response()->json(['success' => true, 'message' => 'El gasto ha sido creado correctamente.'], 200);

        
    
      
    }
    

    /**
     * Genera un gráfico de gastos para una cuenta específica.
     *
     * @param  \Illuminate\Http\Request  $request Datos de la solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del gráfico.
     */
    public function generarGrafico(Request $request)
    {
        // Obtener el ID de la cuenta activa desde la solicitud
        $accountId = $request->id;

        // Obtener los datos de la tabla 'gasto' filtrando por el ID de la cuenta
        $resultados = DB::table('gasto')
            ->join('categoria', 'gasto.id_categoria', '=', 'categoria.id_categoria')
            ->join('cuenta', 'gasto.id_cuenta', '=', 'cuenta.id_cuenta') // Agregar join con la tabla de cuentas
            ->where('cuenta.id_cuenta', $accountId) // Filtrar por el ID de la cuenta activa
            ->select('categoria.nombre as categoria', DB::raw('SUM(importe) as total'), 'categoria.color')
            ->groupBy('categoria.nombre', 'categoria.color')
            ->get();

        // Mapeo de colores disponibles en la base de datos a códigos hexadecimales reconocidos por Google Charts
        $colores = [
            'red' => '#FF0000',
            'blue' => '#0000FF',
            'green' => '#008000',
            'yellow' => '#FFFF00',
            'orange' => '#FFA500',
            'purple' => '#800080',
            'pink' => '#FFC0CB',
            'teal' => '#008080',
            'cyan' => '#00FFFF',
            'lime' => '#00FF00',
            'brown' => '#A52A2A',
            'gray' => '#808080',
        ];

        // Reemplazar los nombres de colores en los resultados con sus códigos hexadecimales correspondientes
        $resultados = $resultados->map(function ($item) use ($colores) {
            $item->color = $colores[$item->color];
            return $item;
        });

        // Retornar los resultados en formato JSON
        return response()->json($resultados);
    }

    /**
     * Carga los gastos asociados a una cuenta específica.
     *
     * @param  int  $id ID de la cuenta.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los gastos asociados a la cuenta.
     */
 

    public function cargarPorCuenta($id)
    {
        $cuenta = Cuenta::findOrFail($id);
    
        // Calcular el total de los gastos asociados a la cuenta
        $totalGastos = $cuenta->gastos()->sum('importe');

        Log::info('Total de gastos: ' . $totalGastos);
        Log::info('Objetivo ahorro: ' . $cuenta->objetivo_ahorro);

        $warningMessage = null; 
        // Verificar si el total de los gastos supera el objetivo de ahorro de la cuenta
        if ($totalGastos > $cuenta->objetivo_ahorro) {

            // Si supera el objetivo de ahorro, mostrar un mensaje de aviso
            $warningMessage = '¡Atención! El total de los gastos de esta cuenta supera el objetivo de ahorro establecido.';
        }
    
        // Cargar los gastos asociados a la cuenta
        $gastos = $cuenta->gastos()->with('categoria')->get();
    
            // Crear dos arrays separados para los gastos y el mensaje de advertencia
    $responseData = [
        'gastos' => $gastos,
        'warning' => $warningMessage,
    ];

    // Devolver los datos de los gastos y el mensaje de advertencia por separado en la respuesta JSON
    return response()->json([
        'gastos' => $gastos,
        'warning' => $warningMessage,
    ]);
    }
/**
 * Devuelve los gastos por cuenta asociados al usuario logueado.
 *
 * @return \Illuminate\Http\JsonResponse
 */
    public function gastosPorCuenta()
    {
        // Obtener el ID del usuario logueado
        $userId = Auth::id();
    
        // Obtener los gastos por cuenta asociados al usuario logueado
        $gastosPorCuenta = DB::table('gasto')
            ->join('cuenta', 'gasto.id_cuenta', '=', 'cuenta.id_cuenta')
            ->where('cuenta.id', $userId) // Filtrar por el ID del usuario logueado
            ->select('cuenta.nombre_cuenta', DB::raw('SUM(gasto.importe) as total_gastos'))
            ->groupBy('cuenta.id_cuenta')
            ->get();
    
        // Devolver los gastos por cuenta asociados al usuario logueado en formato JSON
        return response()->json($gastosPorCuenta);
    }
    
}
