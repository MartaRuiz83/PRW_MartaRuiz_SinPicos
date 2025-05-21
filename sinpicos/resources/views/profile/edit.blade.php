@extends('layouts.app')

@section('title', 'Editar mi perfil')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning text-dark">
          <h4 class="mb-0">Editar perfil</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('perfil.update') }}">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Nombre</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $user->name) }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Correo electrónico</label>
              <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $user->email) }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tipo de Diabetes --}}
            <div class="mb-3">
              <label for="tipo_diabetes" class="form-label fw-semibold">Tipo de diabetes</label>
              <select name="tipo_diabetes" id="tipo_diabetes" class="form-control @error('tipo_diabetes') is-invalid @enderror" required>
                <option value="" disabled>Selecciona una opción</option>
                <option value="Tipo 1" {{ old('tipo_diabetes', $user->tipo_diabetes) == 'Tipo 1' ? 'selected' : '' }}>Tipo 1</option>
                <option value="Tipo 2" {{ old('tipo_diabetes', $user->tipo_diabetes) == 'Tipo 2' ? 'selected' : '' }}>Tipo 2</option>
                <option value="Gestacional" {{ old('tipo_diabetes', $user->tipo_diabetes) == 'Gestacional' ? 'selected' : '' }}>Gestacional</option>
              </select>
              @error('tipo_diabetes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Nueva contraseña (opcional)</label>
              <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
              @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Confirmación --}}
            <div class="mb-4">
              <label for="password_confirmation" class="form-label fw-semibold">Confirmar nueva contraseña</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('home') }}" class="btn btn-outline-warning fw-semibold">
                <i class="ri-arrow-go-back-line me-1"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-warning text-white fw-semibold">
                <i class="ri-save-3-line me-1"></i> Guardar cambios
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
