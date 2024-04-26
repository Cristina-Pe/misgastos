<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

//Ruta para comprobar la BD
Route::get('/test-db-connection', function () {
    try {
        DB::connection()->getPdo();
        return 'Conexión exitosa a la base de datos.';
    } catch (\Exception $e) {
        return 'Error de conexión: ' . $e->getMessage();
    }
});


Route::get('/', function () {
    return view('home');
})->name('inicio');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');


//REGISTRO
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/update', [App\Http\Controllers\Auth\LoginController::class, 'update'])->name('update');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

// Rutas de autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::put('/admin/update-role/{userId}', [UserController::class, 'updateRole'])->name('admin.updateRole');

// Ruta para eliminar un usuario
Route::delete('/admin/delete-user/{userId}', [UserController::class, 'deleteUser'])->name('admin.deleteUser');
Route::delete('/user/{user?}', [UserController::class, 'deleteUser2'])->name('user.delete');

Route::get('/admin/dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
// Rutas de perfil
Route::get('profile', [LoginController::class, 'showProfile'])->name('profile');
Route::get('profile/update', [LoginController::class, 'showUpdateForm'])->name('profile.updateForm');
Route::post('profile/update', [LoginController::class, 'update'])->name('profile.update');


Route::middleware('web')->group(function () {

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');


});


// Ruta para cargar la vista home
Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {

    // Rutas para CRUD de cuentas
    Route::get('/cuentas/create', [CuentasController::class, 'create'])->name('cuentas.create');
    Route::post('/cuentas', [CuentasController::class, 'store'])->name('cuentas.store');
    Route::get('/cuentas/{id}', [CuentasController::class, 'show'])->name('cuentas.show');
    Route::get('/cuentas/{id}/edit', [CuentasController::class, 'edit'])->name('cuentas.edit');
    Route::put('/cuentas/{id}', [CuentasController::class, 'update'])->name('cuentas.update');
    Route::delete('/cuentas/{id}', [CuentasController::class, 'destroy'])->name('cuentas.destroy');
    Route::post('/seleccionar-cuenta', [CuentasController::class, 'seleccionar'])->name('cuentas.seleccionar');
 

     // Rutas para CRUD de categorias
    Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoriasController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [CategoriasController::class, 'store'])->name('categorias.store');
    Route::delete('/categorias/{id}', [CategoriasController::class, 'destroy'])->name('categorias.destroy');
     // Rutas para CRUD de gastos
    Route::get('/gastos', [GastosController::class, 'index'])->name('gastos.index');
    Route::get('/gastos/create', [GastosController::class, 'create'])->name('gastos.create');
    Route::post('/gastos', [GastosController::class, 'store'])->name('gastos.store');
    Route::delete('/gastos/{id}', [GastosController::class, 'destroy'])->name('gastos.destroy');
    Route::post('/gastos/storeOrUpdate', [GastosController::class, 'storeOrUpdate'])->name('gastos.storeOrUpdate');
    Route::get('/gastos/{id}', [GastosController::class, 'show'])->name('gastos.show');
    Route::GET('/cuentas/{id}/gastos', [GastosController::class, 'cargarPorCuenta'])->name('cuentas.gastos');
    Route::get('/grafico-gastos', [GastosController::class, 'generarGrafico'])->name('grafico-gastos');
  Route::get('/gastos/edit/{id}', [GastosController::class, 'edit']);
  Route::put('/gastos/update', [GastosController::class, 'update'])->name('gastos.update');
  Route::get('/gastos-por-cuenta', [GastosController::class, 'gastosPorCuenta']);

 

});







