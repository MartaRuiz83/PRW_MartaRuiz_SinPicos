@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Editar Registro de Glucosa</h1>

    <form action="{{ route('glucosa.update', $glucosa) }}" method="POST" class="mx-auto" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $glucosa->fecha) }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="hora" class="form-label">Hora</label>
            <input type="time" name="hora" id="hora" value="{{ old('hora', $glucosa->hora) }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="momento" class="form-label">Momento</label>
            <select name="momento" id="momento" required class="form-select">
                <option value="ANTES" {{ old('momento', $glucosa->momento) == 'ANTES' ? 'selected' : '' }}>Antes de Comer</option>
                <option value="DESPUÉS" {{ old('momento', $glucosa->momento) == 'DESPUÉS' ? 'selected' : '' }}>Después de Comer</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nivel_glucosa" class="form-label">Nivel de Glucosa (mg/dL)</label>
            <input type="number" name="nivel_glucosa" id="nivel_glucosa" value="{{ old('nivel_glucosa', $glucosa->nivel_glucosa) }}" min="0" required class="form-control">
        </div>

        <button type="submit" class="btn btn-success w-100">Actualizar Registro</button>
    </form>
</div>
@endsection
