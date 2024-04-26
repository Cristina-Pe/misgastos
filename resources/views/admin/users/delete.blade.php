<!-- resources/views/admin/users/delete.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Confirmar Eliminación</h2>
    <p>¿Estás seguro de que deseas eliminar al usuario "{{ $user->name }}"?</p>

    <form method="post" action="{{ route('admin.users.delete', ['id' => $user->id]) }}">
        @csrf
        @method('DELETE')

        <button type="submit">Eliminar</button>
    </form>

    <a href="{{ route('admin.dashboard') }}">Cancelar</a>
@endsection
