<!-- resources/views/admin/users/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Editar Usuario</h2>

    <form method="post" action="{{ route('admin.users.update', ['id' => $user->id]) }}">
        @csrf
        @method('PATCH')

        <label for="name">Nombre:</label>
        <input type="text" name="name" value="{{ $user->name }}" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" value="{{ $user->email }}" required>

        <!-- Agrega aquí los demás campos y la lógica del formulario de edición según tus necesidades -->

        <button type="submit">Actualizar Usuario</button>
    </form>
@endsection
