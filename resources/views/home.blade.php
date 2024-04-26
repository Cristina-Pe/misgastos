@extends('layouts.app') @section('content')
<div class="container mt-5">
<!-- Mensaje de error general -->
@if ($errors->any())
<div class="alert alert-danger">
   <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif
<div class="row">
<div class="col-md-12 text-center">
   @if(Auth::check())
   <p style="margin-bottom: 10px;"><strong>Nombre:</strong> {{ Auth::user()->name }} <strong>Email:</strong> {{ Auth::user()->email }} <button class="btn btn-primary" onclick="toggleForm()" style="margin-left: 10px;">Editar perfil</button></p>
   <form id="editForm" action="{{ route('user.update', Auth::user()) }}" method="POST" style="display:none;">
      @csrf
      @method('PUT')
      <!-- Campo de nombre -->
      <div class="form-group row">
         <label for="name" class="col-sm-3 col-form-label text-right">Nombre:</label>
         <div class="col-sm-9">
            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control">
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
      </div>
      <!-- Campo de correo electrónico -->
      <div class="form-group row">
         <label for="email" class="col-sm-3 col-form-label text-right">Correo electrónico:</label>
         <div class="col-sm-9">
            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control">
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
      </div>
      <!-- Campo de contraseña -->
      <div class="form-group row">
         <label for="password" class="col-sm-3 col-form-label text-right">Nueva contraseña:</label>
         <div class="col-sm-9">
            <input type="password" id="password" name="password" class="form-control">
            @error('password')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
      </div>
      <!-- Campo de confirmación de contraseña -->
      <div class="form-group row">
         <label for="password_confirmation" class="col-sm-3 col-form-label text-right">Confirmar nueva contraseña:</label>
         <div class="col-sm-9">
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            @error('password_confirmation')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
      </div>
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
   </form>
   <div class="form-group d-flex justify-content-between align-items-center">
      <h3>Seleccionar Cuenta</h3>
      @if(count($cuentas) > 0)
      <select id="cuenta_activa" name="cuenta_activa" class="form-control" required>
         @foreach ($cuentas as $cuenta)
         <option value="{{ $cuenta->id_cuenta }}">{{ $cuenta->nombre_cuenta }}</option>
         @endforeach
      </select>
      <button id="seleccionarBtn" class="btn btn-primary">Seleccionar</button>
      @else
      <p>No hay cuentas disponibles para este usuario. ¡Create una!</p>
      @endif
   </div>
   <button onclick="toggleCuentasForm()" class="btn btn-dark btn-lg px-3 mx-3">Crear cuenta</button>
   <button onclick="toggleCategoriasForm()" class="btn btn-dark btn-lg px-3 mx-3">Crear categoría</button>
   <button onclick="generarGraficoBarras()" class="btn btn-dark btn-lg px-3 mx-3">Generar Gráfico de Barras</button>
   <button id="boton-cerrar-grafico" onclick="cerrarGrafico()" class="btn btn-danger btn-lg" style="display: none;">Cerrar Gráfico</button>
   </BR>
   <div id="grafico-gastos-container" style="display: none;">
      <div id="grafico-gastos"></div>
      <button id="ocultar-grafico" class="btn btn-dark btn-lg mt-3">X Ocultar Gráfico</button>
   </div>
   @if(session('error'))
   <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 10px;">
      {{ session('error') }}
      <button type="button" class="close" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
   </div>
   @endif
   @if(session('success'))
   <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 10px;">
      {{ session('success') }}
      <button type="button" class="close" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
   </div>
   @endif
   <div id="contenido" class="form-group d-flex justify-content-between align-items-center">
      <canvas id="grafico-barras" width="400" height="200" style="display: none; margin: auto;"></canvas>
   </div>
   <div class="container mt-5 " id="contenidogastos" style="display: none;">
      <div class="row">
         <div class="col-md-6">
            <div class="text-center">
               <!-- Contenido del lado izquierdo -->
               <!-- Listado de Gastos -->
               <div id="listaGastos" style="{{ app('request')->input('openGastos') ? 'display: block;' : 'display: none;' }}}">
               </div>
               <div id="paginationContainer"></div>
               <div id="botones-container" class="mt-3 text-center" style="display: none;">
                  <button id="boton-generar-grafico" class="btn btn-dark btn-lg mx-3">Generar Gráfico</button>
                  <button id="boton-generar-pdf" class="btn btn-dark btn-lg mx-3">Generar PDF</button>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <!-- Contenido del lado derecho -->
            <!-- Formulario de Creación de Gastos -->
            <form id="gasto-form" action="{{ route('gastos.storeOrUpdate') }}" method="POST" style="display: none;">
            <div id="error-container"></div>
               <h2 id="form-title">Crear Nuevo Gasto</h2>
               @csrf
               <input type="hidden" name="gasto_id" id="gasto_id">
               <div class="form-group">
                  <label for="descripcion">Descripción:</label>
                  <input type="text" id="descripcion" name="descripcion" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="fecha">Fecha:</label>
                  <input type="date" id="fecha" name="fecha" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="importe">Importe:</label>
                  <input type="number" id="importe" name="importe" class="form-control" step="0.01" required>
               </div>
               <div class="form-group">
                  <label for="categoria">Categoría:</label>
                  <select id="categoria" name="categoria" class="form-control" required>
                     <option value="">Seleccione una categoría</option>
                     @if(count($categorias) > 0)
                     @foreach ($categorias as $categoria)
                     <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                     @endforeach
                     @else
                     <option value="" disabled>No hay categorías disponibles para esta cuenta.</option>
                     <option value="" disabled>Por favor, cree una nueva categoría.</option>
                     @endif
                  </select>
               </div>
               <div class="form-group">
                  <label for="cuenta">Cuenta:</label>
                  <select id="cuenta" name="cuenta" class="form-control" required disabled>
                     <option value="">Seleccione una cuenta</option>
                     @foreach ($cuentas as $cuenta)
                     <option value="{{ $cuenta->id_cuenta }}">{{ $cuenta->nombre_cuenta }}</option>
                     @endforeach
                  </select>
               </div>
               <button type="button" id="submit-button" class="btn btn-primary" onclick="crearGasto()">Crear Gasto</button>
            </form>
            <!-- Formulario para Editar Gasto -->
            <form id="editar-gasto-form" action="{{ route('gastos.update') }}" method="POST" style="display: none;">
               <h2 id="form-title">Editar gasto</h2>
               @csrf
               <input type="hidden" name="_method" value="PUT">
               <input type="hidden" name="gasto_id" id="edit_gasto_id">
               <div class="form-group">
                  <label for="descripcion">Descripción</label>
                  <input type="text" class="form-control" id="edit_descripcion" name="descripcion">
               </div>
               <div class="form-group">
                  <label for="fecha">Fecha</label>
                  <input type="date" class="form-control" id="edit_fecha" name="fecha">
               </div>
               <div class="form-group">
                  <label for="importe">Importe</label>
                  <input type="number" class="form-control" id="edit_importe" name="importe">
               </div>
               <div class="form-group">
                  <label for="categoria">Categoría</label>
                  <select id="edit_categoria" name="categoria" class="form-control" required>
                     <option value="">Seleccione una categoría</option>
                     @foreach ($categorias as $categoria)
                     <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                     @endforeach
                  </select>
               </div>
               <button type="submit" class="btn btn-primary" onclick="editarGasto()">Actualizar Gasto</button>
            </form>
         </div>
      </div>
   </div>
   <div >
      <!-- Formulario de Creación de Cuentas -->
      <div id="cuentasForm" style="display: none;">
         <div class="container-fluid" id="cuentasForm">
            <form action="{{ route('cuentas.store') }}" method="POST" style="white-space: nowrap;">
               @csrf
               <div class="form-group" style="display: inline-block; margin-right: 10px;">
                  <label for="nombre_cuenta" style="display: inline-block; width: 150px;">Nombre de la Cuenta:</label>
                  <input type="text" id="nombre_cuenta" name="nombre_cuenta" class="form-control"style="display: inline-block; width: 300px;"required>
               </div>
               <div class="form-group" style="display: inline-block; margin-right: 10px;">
                  <label for="objetivo_ahorro" style="display: inline-block; width: 150px;">Objetivo de Ahorro:</label>
                  <input type="number" id="objetivo_ahorro" name="objetivo_ahorro" class="form-control" style="display: inline-block; width: 300px;" required>
               </div>
               <button type="submit" class="btn btn-light" style="display: inline-block;">Crear Cuenta</button>
            </form>
         </div>
      </div>
      <!-- Listado de Categorías -->
      <div  id="categoriasForm" style="display: none;">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-6">
                  <h2>Listado de Categorías</h2>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Nombre de la Categoría</th>
                           <th>Acciones</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse ($categorias as $categoria)
                        <tr>
                           <td class="categoria" style="background-color: {{ $categoria->color }}">{{ $categoria->nombre }}</td>
                           <td>
                              <form action="{{ route('categorias.destroy', $categoria->id_categoria) }}" method="POST">
                                 @csrf @method('DELETE')
                                 <button type="submit" class="btn btn-danger">Eliminar</button>
                              </form>
                           </td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="2">No hay categorías.</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
               <!-- Formulario de Creación de Categorías -->
               <div class="col-md-6">
                  <div>
                     <h2>Crear Nueva Categoría</h2>
                     <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                           <label for="nombre">Nombre de la Categoría:</label>
                           <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label for="color">Color de la Categoría:</label>
                           <select name="color" id="color" class="form-control" onchange="mostrarColor()">
                              <option value="" selected disabled>Selecciona un color</option>
                              @foreach ($colores as $color => $col)
                              <option value="{{ $col }}" style="background-color: {{ $col }};"></option>
                              @endforeach
                           </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Categoría</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         @else
         <!-- Contenido para usuarios no autenticados -->
         <div class="position-relative">
            <img src="{{ asset('images/banner.png') }}" alt="Banner" class="img-fluid w-100 h-100" style="object-fit: cover;">
            <div class="position-absolute top-50 start-50 translate-middle text-center">
               <h1 style="color: black; font-weight: bold;">CONTROLA LO QUE GASTAS</h1>
               <h1 style="color: black; font-weight: bold;">GASTA EN LO QUE QUIERAS</h1>
               <a href="{{ route('register') }}" class="btn btn-dark btn-lg px-3 mx-3">Registro</a>
            </div>
         </div>
         @endif
      </div>
   </div>
</div>
<script>

//Función para cerrar una alerta específica.
function cerrarAlerta(alertId) {
		var alerta = document.getElementById(alertId);
		alerta.style.display = "none";
	}
// Agrega eventos de clic a los botones de cierre
var closeButtons = document.querySelectorAll(".close");
closeButtons.forEach(function(closeButton) {
	closeButton.addEventListener("click", function() {
		var alertId = closeButton.parentNode.id;
		cerrarAlerta(alertId);
	});
});

//Función para limpiar el contenido del elemento ontenido y ocultar otros elementos relacionados.
function limpiarContenido() {
	document.getElementById("contenido").innerHTML = '';
	document.getElementById("contenidogastos").style.display = "none";
	document.getElementById("boton-cerrar-grafico").style.display = "none";
}

//Función para mostrar el formulario de creación de cuentas.
function toggleCuentasForm() {
	limpiarContenido();
	var form = document.getElementById("cuentasForm");
	document.getElementById("contenido").innerHTML = form.innerHTML;
}

//Función para mostrar el formulario de creación de categorías.
function toggleCategoriasForm() {
	limpiarContenido();
	var form = document.getElementById("categoriasForm");
	document.getElementById("contenido").innerHTML = form.innerHTML;
}

//Función para crear un gasto.
function crearGasto() {
	var form = document.getElementById("gasto-form");
	var selectedAccountId = document.getElementById("cuenta_activa").value;
	var formData = new FormData(form);
	formData.append('cuenta', selectedAccountId);
	fetch('/gastos/storeOrUpdate', {
			method: 'POST',
			body: formData
		})
		.then(response => {
			console.log(response);
         if (response.ok) {
                return response.json();
            } else if (response.status === 422) {
               return response.json().then(errors => {
                    // Limpiar los mensajes de error anteriores
                    document.getElementById("error-container").innerHTML = '';
                    // Mostrar los errores en el contenedor
                    var errorContainer = document.getElementById("error-container");
                    var errorList = document.createElement("ul");
                    errorList.style.color = 'red'; // Cambiar el color del texto a rojo
                    Object.entries(errors).forEach(([fieldName, errorMessage]) => {
                        if (Array.isArray(errorMessage)) {
                            errorMessage.forEach(msg => {
                                var errorItem = document.createElement("li");
                                errorItem.textContent = `${msg}`;
                                errorItem.style.listStyleType = 'none';
                                errorList.appendChild(errorItem);
                            });
                        } else {
                            var errorItem = document.createElement("li");
                            errorItem.textContent = `${fieldName}: ${errorMessage}`;
                            errorList.appendChild(errorItem);
                        }
                    });
                    errorContainer.appendChild(errorList);
                    throw new Error('Error de validación');
                });
      
            } else {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
		})
		.then(data => {
         document.getElementById("error-container").innerHTML = '';
			console.log(data.message);
			cargarListadoGastos(selectedAccountId);
			// Mostrar el listado de gastos después de la creación
			var listaGastosContainer = document.getElementById("listaGastos");
			listaGastosContainer.style.display = "block";
			limpiarFormulario();
		})
		.catch(error => {
			console.error('Error:', error);
		});
}

//Función para limpiar los datos del formulario de creación de gastos.
function limpiarFormulario() {
	document.getElementById('descripcion').value = '';
	document.getElementById('fecha').value = '';
	document.getElementById('importe').value = '';
	document.getElementById('categoria').value = '';
}

//Función para ocultar el gráfico de gastos.
document.getElementById("ocultar-grafico").addEventListener("click", function() {
	document.getElementById("grafico-gastos-container").style.display = "none";
});

//Función para mostrar los gastos asociados a una cuenta específica.
 document.getElementById("seleccionarBtn").addEventListener("click", function() {
	document.getElementById("contenido").innerHTML = '';
	document.getElementById("contenidogastos").style.display = "block";
	var selectedAccountId = document.getElementById("cuenta_activa").value;
	cargarListadoGastos(selectedAccountId);
	document.getElementById("gasto-form").style.display = "none";
   document.getElementById("error-container").innerHTML = '';
});

//Función para mostrar el color seleccionado en un elemento select.
function mostrarColor() {
	var select = document.getElementById("color");
	var colorSeleccionado = select.options[select.selectedIndex].value;
	select.style.backgroundColor = colorSeleccionado;
}

//Función para mostrar u ocultar el formulario de creación de un nuevo gasto.
function mostrarFormularioNuevoGasto() {
	var selectedAccountId = document.getElementById("cuenta_activa").value;
	document.getElementById("cuenta").value = selectedAccountId;
	var form = document.getElementById("gasto-form");
	form.style.display = form.style.display === "none" ? "block" : "none";
	var editForm = document.getElementById("editar-gasto-form");
	editForm.style.display = "none";
}

//Función para mostrar el formulario de edición de un gasto con los datos del gasto a modificar.
function mostrarFormularioEditarGasto(gastoId) {
	fetch('/gastos/' + gastoId)
		.then(response => response.json())
		.then(gasto => {
			console.log(gasto); // Verifica los datos del gasto en la consola
			// Rellena los campos del formulario con los datos del gasto
			document.getElementById('edit_gasto_id').value = gasto.id_gasto;
			document.getElementById('edit_descripcion').value = gasto.descripcion;
			document.getElementById('edit_fecha').value = gasto.fecha;
			document.getElementById('edit_importe').value = gasto.importe;
			document.getElementById('edit_categoria').value = gasto.id_categoria;
			// Mostrar u ocultar el formulario de edición de gasto
			toggleEditarGastoForm(); 
		})
		.catch(error => console.error('Error al obtener los datos del gasto:', error));
}

// Función para mostrar u ocultar el formulario de edición de un gasto.
function toggleEditarGastoForm() {
	var form = document.getElementById('editar-gasto-form');
	form.style.display = form.style.display === 'none' ? 'block' : 'none';
	var editForm = document.getElementById("gasto-form");
	editForm.style.display = "none";
}

//Función para mostrar u ocultar un formulario específico.
function toggleForm() {
	var form = document.getElementById("editForm");
	form.style.display = (form.style.display == "none") ? "block" : "none";
}
// Variables para controlar la paginación
let currentPage = 1;
const itemsPerPage = 5;

// Función para cargar el listado de gastos.
function cargarListadoGastos(selectedAccountId) {
	if (!selectedAccountId) {
		selectedAccountId = document.getElementById("cuenta_activa").value;
	}
	const listaGastosContainer = document.getElementById("listaGastos");
	listaGastosContainer.style.display = "block";
	fetch(`/cuentas/${selectedAccountId}/gastos?page=${currentPage}&perPage=${itemsPerPage}`)
		.then(response => response.json())
		.then(data => {
			const gastos = data.gastos;
			gastos.reverse();
			const startIndex = (currentPage - 1) * itemsPerPage;
			const endIndex = startIndex + itemsPerPage;
			const gastosToShow = gastos.slice(startIndex, endIndex);
			var listaGastosContainer = document.getElementById("listaGastos");
			listaGastosContainer.innerHTML = '<h2>Listado de Gastos</h2>';
			if (data.warning) {
				listaGastosContainer.innerHTML += `<div class="alert alert-warning">${data.warning}</div>`;
			}
			if (gastosToShow.length > 0) {
				var tableHTML = '<table class="table" id="tabla-gasto"><thead><tr><th>Descripción</th><th>Fecha</th><th>Importe</th><th id="acciones-column">Acciones</th></tr></thead><tbody>';
				gastosToShow.forEach(gasto => {
					tableHTML += '<tr><td class="categoria" style="background-color: ' + gasto.categoria.color + '">' + gasto.descripcion + '</td><td>' + gasto.fecha + '</td><td>' + gasto.importe + ' €</td><td><button class="btn btn-primary btn-sm editar-gasto" onclick="mostrarFormularioEditarGasto(' + gasto.id_gasto + ')">Editar</button><button class="btn btn-danger btn-sm eliminar-gasto" onclick="eliminarGasto(' + gasto.id_gasto + ', ' + selectedAccountId + ')">Eliminar</button></td></tr>';
				});
				tableHTML += '</tbody></table>';
				listaGastosContainer.innerHTML += tableHTML;
				mostrarBotonesPaginacion(data.gastos.length);
				console.log(data.gastos.length);
			} else {
				listaGastosContainer.innerHTML += '<p>No hay gastos asociados a esta cuenta.</p>';
			}
			listaGastosContainer.innerHTML += '<button id="mostrarFormularioNuevoGasto" class="btn btn-primary mt-3">Crear Nuevo Gasto</button>';
			document.getElementById("mostrarFormularioNuevoGasto").addEventListener("click", function() {
				mostrarFormularioNuevoGasto();
			});
			document.getElementById("paginationContainer").style.display = "block";
			document.getElementById("botones-container").style.display = "block";
			// Agregar event listener al botón "Generar Gráfico"
			document.getElementById("boton-generar-grafico").addEventListener('click', function() {
				generarGrafico();
			});
			// Agregar event listener al botón "Generar PDF"
			document.getElementById("boton-generar-pdf").addEventListener('click', function() {
				generarPDF();
			});
			// Almacenar el ID de la cuenta seleccionada en el almacenamiento local
			localStorage.setItem('idCuentaSeleccionada', selectedAccountId);
		})
		.catch(error => console.error('Error al obtener los gastos:', error));
}
// Función para mostrar los botones de paginación.
function mostrarBotonesPaginacion(totalItems) {
	const totalPages = Math.ceil(totalItems / itemsPerPage);
	const paginationContainer = document.getElementById("paginationContainer");
	paginationContainer.innerHTML = '';
	if (currentPage > 1) {
		paginationContainer.innerHTML += `<button class="btn btn-primary" onclick="gotoPage(${currentPage - 1})">Anterior</button>`;
	}
	if (currentPage < totalPages) {
		paginationContainer.innerHTML += `<button class="btn btn-primary" onclick="gotoPage(${currentPage + 1})">Siguiente</button>`;
	}
}

// Función para navegar a una página específica en la paginación.
function gotoPage(page) {
	currentPage = page;
	cargarListadoGastos();
}

//Función para generar un PDF a partir de la tabla de gastos.
function generarPDF() {
	const tablaSinAcciones = document.createElement('table');
	tablaSinAcciones.className = 'table'; 
	const tablaOriginal = document.getElementById('tabla-gasto');
	const filasOriginales = tablaOriginal.querySelectorAll('tr');
	filasOriginales.forEach((filaOriginal, rowIndex) => {
		const filaCopia = document.createElement('tr');
		filaOriginal.querySelectorAll('th, td').forEach((celda, colIndex) => {
			if (colIndex !== 3) { 
				const celdaCopia = document.createElement(celda.tagName);
				celdaCopia.textContent = celda.textContent;
				filaCopia.appendChild(celdaCopia);
			}
		});
		tablaSinAcciones.appendChild(filaCopia);
	});
	const titulo = document.createElement('h2');
	titulo.textContent = 'Listado de Gastos';
	titulo.style.marginBottom = '10px'; 
	const contenedor = document.createElement('div');
	contenedor.appendChild(titulo);
	contenedor.appendChild(tablaSinAcciones);

	const options = {
		filename: 'listado_gastos.pdf', // Nombre del archivo PDF
		margin: 10,
		jsPDF: {
			format: 'a4',
			orientation: 'portrait'
		} 
	};

	html2pdf().from(contenedor).set(options).save();
}

//Función para generar un gráfico de gastos por categoría.
function generarGrafico() {
	limpiarContenido();
	var graficoContainer = document.getElementById("contenido");
	graficoContainer.style.display = "block";
	var selectedAccountId = document.getElementById("cuenta_activa").value;
	google.charts.load('current', {
		packages: ['corechart']
	});
	fetch('/grafico-gastos?id=' + selectedAccountId)
		.then(response => response.json())
		.then(data => {
			console.log('Datos obtenidos del servidor:', data);
			const dataTable = new google.visualization.DataTable();
			dataTable.addColumn('string', 'Categoría');
			dataTable.addColumn('number', 'Total');
			dataTable.addColumn({
				type: 'string',
				role: 'style'
			}); 
			data.forEach(item => {
				const color = item.color.substring(1);
				dataTable.addRow([item.categoria, parseFloat(item.total), 'color: #' + color]);
			});
			const options = {
				title: 'Gastos por Categoría',
				width: '100%',
				height: 400,
				pieSliceText: 'label', 
				pieSliceTextStyle: {
					color: 'black'
				},
				slices: data.map(item => ({
					color: item.color
				}))
			};
	
			const chart = new google.visualization.PieChart(document.getElementById('contenido'));
			var botonCerrarGrafico = document.createElement("button");
			botonCerrarGrafico.innerHTML = "X Cerrar";
			botonCerrarGrafico.classList.add("btn", "btn-lg", "btn-link");
			chart.getContainer().style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.1)";
			botonCerrarGrafico.style.position = "absolute";
			botonCerrarGrafico.addEventListener('click', function() {
				limpiarContenido();
				chart.getContainer().style.boxShadow = ""; 
				botonCerrarGrafico.style.display = "none";
			});
			graficoContainer.parentNode.insertBefore(botonCerrarGrafico, graficoContainer);
			chart.draw(dataTable, options);
		})
		.catch(error => console.error('Error al obtener los datos:', error));
}

// Función para eliminar un gasto.
function eliminarGasto(gastoId) {
	var selectedAccountId = document.getElementById("cuenta_activa").value;
	if (confirm('¿Estás seguro de que deseas eliminar este gasto?')) {
		fetch('/gastos/' + gastoId, {
				method: 'DELETE',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				}
			})
			.then(response => {
				if (response.ok) {
					return response.json();
				}
				throw new Error('Error al eliminar el gasto');
			})
			.then(data => {
				
				console.log(data.message); 
				cargarListadoGastos(selectedAccountId);
			})
			.catch(error => {
				console.error('Error:', error);
			});
	}
}
let graficoBarras = null; // Variable global para almacenar la instancia del gráfico

// Función para generar un gráfico de barras que muestra los gastos por cuenta.
function generarGraficoBarras() {
	limpiarContenido();
	fetch('/gastos-por-cuenta')
		.then(response => response.json())
		.then(data => {
         if (data.length === 0) {
            const mensaje = 'No hay datos disponibles para generar el gráfico.';
                // Mostrar el mensaje de error directamente
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger'; 
                errorDiv.innerHTML =mensaje 
                document.getElementById('contenido').appendChild(errorDiv);
                return;
            }
			const labels = data.map(item => item.nombre_cuenta);
			const valores = data.map(item => item.total_gastos);
			// Crear el canvas del gráfico
			const canvas = document.createElement('canvas');
			canvas.id = 'grafico-barras';
			canvas.width = 600; // Ancho deseado del gráfico
			canvas.height = 300; // Alto deseado del gráfico
			canvas.style.display = 'block';
			canvas.style.margin = 'auto';
			// Agregar el canvas al div 'contenido'
			document.getElementById('contenido').appendChild(canvas);
			const ctx = canvas.getContext('2d');
			graficoBarras = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Total de gastos por cuenta',
						data: valores,
						backgroundColor: '#F878CD',
						borderColor: '#F878CD',
						borderWidth: 1
					}]
				},
				options: {
					responsive: false, 
					maintainAspectRatio: false,
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
			// Mostrar el botón "Cerrar Gráfico"
			document.getElementById('boton-cerrar-grafico').style.display = 'inline-block';
		})
		.catch(error => console.error('Error al obtener los datos:', error));
}

// Función para cerrar el gráfico de barras y limpiar los recursos.
function cerrarGrafico() {
	// Verificar si existe un gráfico para destruir
	if (graficoBarras) {
		graficoBarras.destroy(); // Destruir la instancia del gráfico
		graficoBarras = null; // Limpiar la variable global
		document.getElementById('grafico-barras').style.display = 'none'; // Ocultar el canvas del gráfico
		document.getElementById('boton-cerrar-grafico').style.display = 'none'; // Ocultar el botón "Cerrar Gráfico"
	}
}

</script>
@endsection