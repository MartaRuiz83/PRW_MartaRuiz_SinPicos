{{-- resources/views/glucosa/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="card mx-auto" style="max-width: 700px;">
    {{-- Header en amarillo warning con texto negro --}}
    <div class="card-header bg-warning text-dark text-center">
      <h2 class="mb-0">Editar Registro de Glucosa</h2>
    </div>
    <div class="card-body">

      <form action="{{ route('glucosa.update', $glucosa) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
          {{-- Fecha --}}
          <div class="col-md-6">
            <label for="fecha" class="form-label">Fecha</label>
            <input
              type="date"
              id="fecha"
              name="fecha"
              value="{{ old('fecha', $glucosa->fecha) }}"
              class="form-control @error('fecha') is-invalid @enderror"
              required
            >
            @error('fecha')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Hora --}}
          <div class="col-md-6">
            <label for="hora" class="form-label">Hora</label>
            <input
              type="time"
              id="hora"
              name="hora"
              value="{{ old('hora', $glucosa->hora) }}"
              class="form-control @error('hora') is-invalid @enderror"
              required
            >
            @error('hora')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Momento --}}
        <div class="mb-3 mt-4">
          <label for="momento" class="form-label">Momento</label>
          <select
            id="momento"
            name="momento"
            class="form-select @error('momento') is-invalid @enderror"
            required
          >
            <option value="ANTES"   {{ old('momento', $glucosa->momento) == 'ANTES'   ? 'selected' : '' }}>
              Antes de Comer
            </option>
            <option value="DESPUÉS" {{ old('momento', $glucosa->momento) == 'DESPUÉS' ? 'selected' : '' }}>
              Después de Comer
            </option>
          </select>
          @error('momento')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Nivel de glucosa --}}
        <div class="mb-4">
          <label for="nivel_glucosa" class="form-label">Nivel de Glucosa (mg/dL)</label>
          <input
            type="number"
            id="nivel_glucosa"
            name="nivel_glucosa"
            value="{{ old('nivel_glucosa', $glucosa->nivel_glucosa) }}"
            min="0"
            required
            class="form-control @error('nivel_glucosa') is-invalid @enderror"
          >
          @error('nivel_glucosa')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-end">
          <a href="{{ route('glucosa.index') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-warning">Actualizar Registro</button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
