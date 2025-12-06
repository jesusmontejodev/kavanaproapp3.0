@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Tarea</h2>

    <form method="POST" action="{{ route('tareas.update', $tarea->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="id_grupo_tarea">Grupo de Tarea:</label>
            <select name="id_grupo_tarea" id="id_grupo_tarea" required>
                @foreach($grupoTareas as $grupo)
                    <option value="{{ $grupo->id }}" {{ $tarea->id_grupo_tarea == $grupo->id ? 'selected' : '' }}>
                        {{ $grupo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ $tarea->nombre }}" required>
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion">{{ $tarea->descripcion }}</textarea>
        </div>

        <div>
            <label for="orden">Orden:</label>
            <input type="number" name="orden" id="orden" value="{{ $tarea->orden }}">
        </div>

        <button type="submit">Actualizar Tarea</button>
        <a href="{{ route('grupotareas.show', $tarea->id_grupo_tarea) }}">Cancelar</a>
    </form>
</div>
@endsection
