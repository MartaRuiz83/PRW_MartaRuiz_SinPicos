{{-- resources/views/recomendations.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  /* Container & Grid */
  .rec-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
  }
  /* Card Base */
  .rec-card {
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    background: #fff;
    transition: transform .3s, box-shadow .3s;
  }
  .rec-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
  }
  /* Gradient Accent Bar */
  .rec-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 6px;
    background: linear-gradient(90deg, #7d3ced, #c77dff);
  }
  /* Icon */
  .rec-icon {
    font-size: 3rem;
    color: #7d3ced;
    margin-bottom: 1rem;
  }
  /* Body Content */
  .rec-body {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .rec-body h5 {
    font-size: 1.5rem;
    margin-bottom: .75rem;
    color: #333;
  }
  .rec-body p {
    flex-grow: 1;
    color: #555;
    line-height: 1.5;
  }
  /* Animated Background Overlay */
  .rec-overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: radial-gradient(circle at center, rgba(125,60,237,0.2), transparent 70%);
    opacity: 0;
    transition: opacity .5s;
  }
  .rec-card:hover .rec-overlay {
    opacity: 1;
  }
</style>

{{-- Header --}}
<div class="text-center mt-5">
  <h1 class="display-4 fw-bold" style="color:#7d3ced;">Recomendaciones</h1>
  <p class="lead text-muted">Consejos interactivos que mejoran tu día a día</p>
</div>

{{-- Cards Grid --}}
<div class="container rec-container">
  @if($recs->isEmpty())
    <p class="text-center text-muted w-100">No hay recomendaciones disponibles.</p>
  @else
    @foreach($recs as $rec)
      <div class="rec-card">
        <div class="rec-overlay"></div>
        <div class="rec-body text-center d-flex flex-column">
          <i class="ri-lightbulb-flash-line rec-icon mx-auto"></i>
          <h5>{{ $rec->titulo }}</h5>
          <p>{{ $rec->descripcion }}</p>
        </div>
      </div>
    @endforeach
  @endif
</div>
@endsection
