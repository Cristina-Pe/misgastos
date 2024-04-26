<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">



    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    



    <style>
        /* Estilos personalizados */
        .card-header{
            background-color: #F8C954; 
            
        }

        .form-control:focus {
    border-color: #ced4da; /* Cambia el color del borde cuando el campo está enfocado */
    box-shadow: 0 0 0 0.2rem #F8C954; /* Cambia el color del blur cuando el campo está enfocado */
    background-color: white; /* Cambiar el color de fondo */
}

.form-control{
    background-color: white;
}

.table>:not(caption)>*>*{
    background-color: white;
}

#contenido{
    margin:40px;
}

#cuentasForm{

    padding:10px;
    border-radius: 10px; 
    background-color:  #F878CD;
 }

 #categoria-box{
    border: 2px solid black;
    padding:10px;
    border-radius: 10px; 
 }


.btn-primary{
    margin:10px;
    --bs-btn-color: black;
    --bs-btn-bg: #F8C95499;
    --bs-btn-border-color:  #F8C95499;
    --bs-btn-hover-color: black;
    --bs-btn-hover-bg:  #F8C954;
    --bs-btn-hover-border-color:   #F8C954;
    --bs-btn-focus-shadow-rgb: 225, 83, 97;
    --bs-btn-active-color:  black;
    --bs-btn-active-bg: #F8C95498;
    --bs-btn-active-border-color:  #F8C95498;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #F8C954;
    --bs-btn-disabled-border-color:  #F8C95498;
   
}

#grafico-gastos-container{
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
    margin-top:20px
}

.table tbody  {
    background-color: white;
}

        .card{
            border: 2px solid black;
            color:black;
            background-color:white; 
        }
        .ventajas-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .ventajas-column {
            background-color: #F878CD; 
            padding: 20px;
            border-radius: 10px;
            color: black;
        }
        .ventajas-column:not(:last-child) {
            margin-bottom: 20px;
        }

        .py-4{
            background-color:white;  
        }

        html {
            position: relative;
            min-height: 100%;
            background-color:white;  
        }
        body {
            margin-bottom: 60px; /* Altura del footer */
        }
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px; /* Altura del footer */
            background-color: #333;
            color: #fff;
            text-align: center;
            line-height: 60px; /* Centrar verticalmente el contenido del footer */
        }
    </style>
</head>
<body>


    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-height: 50%; max-width: 50%;">

</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest

                        <a class="navbar-brand" href="{{ route('inicio') }}">
                        {{ __('Inicio') }}</a>
                        <li class="nav-item">
    <a class="nav-link" href="{{ route('about') }}">{{ __('Acerca de') }}</a>
</li>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registro') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end " aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest

                        @auth
                    @if (auth()->user()->role === 'admin')
                    <li style="padding-top: 8px;"><a href="{{ route('admin.dashboard') }}" >Dashboard Admin</a></li>
                     @endif
                     @endauth

                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <footer class="bg-dark text-light py-3">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    &copy; {{ date('Y') }} MisGastos
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
