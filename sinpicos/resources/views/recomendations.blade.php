@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h2 class="text-center fw-bold mb-4" style="color:#7d3ced;">
    Nuestras Recomendaciones
  </h2>

  @if($recs->isEmpty())
    <p class="text-center text-muted">De momento no hay recomendaciones.</p>
  @else
    <div class="row g-4">
      @foreach($recs as $rec)
        <div class="col-sm-6 col-lg-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $rec->titulo }}</h5>
              <p class="card-text text-muted mb-4">
                {{ \Illuminate\Support\Str::limit($rec->descripcion, 140) }}
              </p>
              <a href="{{ route('admin.recomendations.show', $rec) }}"
                 class="mt-auto btn btn-outline-primary">
                Leer más
              </a>
            </div>
            <div class="card-footer text-end text-secondary">
              {{ $rec->created_at->format('d/m/Y') }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
