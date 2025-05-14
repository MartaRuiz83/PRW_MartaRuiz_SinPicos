@extends('layouts.app')

@section('content')
<div class="container py-5">
  {{-- Título --}}
  <h1 class="text-center mb-4" style="color:#c0392b;">Registrar Nivel de Glucosa</h1>

  {{-- Mensajes --}}
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Formulario --}}
  <form action="{{ route('glucosa.store') }}" method="POST"
        class="mx-auto bg-white p-4 rounded shadow-sm"
        style="max-width: 500px; border:1px solid #f0c0c0;">
    @csrf

    {{-- Fecha --}}
    <div class="mb-3">
      <label for="fecha" class="form-label" style="color:#6e2c00;">Fecha</label>
      <input type="date" name="fecha" id="fecha"
             value="{{ old('fecha') }}" required
             class="form-control"
             style="border-color:#e6b0aa;">
    </div>

    {{-- Hora --}}
    <div class="mb-3">
      <label for="hora" class="form-label" style="color:#6e2c00;">Hora</label>
      <input type="time" name="hora" id="hora"
             value="{{ old('hora') }}" required
             class="form-control"
             style="border-color:#e6b0aa;">
    </div>

    {{-- Momento --}}
    <div class="mb-3">
      <label for="momento" class="form-label" style="color:#6e2c00;">Momento</label>
      <select name="momento" id="momento"
              required
              class="form-select"
              style="border-color:#e6b0aa;">
        <option value="">Selecciona un momento</option>
        <option value="ANTES" {{ old('momento')=='ANTES'?'selected':'' }}>Antes de Comer</option>
        <option value="DESPUÉS" {{ old('momento')=='DESPUÉS'?'selected':'' }}>Después de Comer</option>
      </select>
    </div>

    {{-- Nivel de glucosa --}}
    <div class="mb-4">
      <label for="nivel_glucosa" class="form-label" style="color:#6e2c00;">
        Nivel de Glucosa (mg/dL)
      </label>
      <input type="number" name="nivel_glucosa" id="nivel_glucosa"
             value="{{ old('nivel_glucosa') }}"
             min="0" required
             class="form-control"
             style="border-color:#e6b0aa;">
    </div>

    {{-- Botones --}}
    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-outline-danger"
              style="border-width:2px;">
        <i class="ri-drop-fill"></i> Registrar
      </button>
      <a href="{{ route('glucosa.index') }}" class="btn btn-secondary">
        <i class="ri-arrow-left-line"></i> Cancelar
      </a>
    </div>
  </form>
</div>
@endsection
