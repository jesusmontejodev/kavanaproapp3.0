@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalles de la Tarea</h2>

    <div class="card">
        <h3>{{ $tarea->nombre }}</h3>
        <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
        <p><strong>Grupo:</strong> {{ $tarea->grupoTarea->nombre }}</p>
        <p><strong>Orden:</strong> {{ $tarea->orden }}</p>
        <p><strong>Creado:</strong> {{ $tarea->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="actions">
        <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn">Editar</a>
        <form method="POST" action="{{ route('tareas.destroy', $tarea->id) }}" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
        </form>
        <a href="{{ route('grupotareas.show', $tarea->id_grupo_tarea) }}">Volver al Grupo</a>
    </div>
</div>
@endsection
