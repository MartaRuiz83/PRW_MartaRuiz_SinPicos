{{-- resources/views/admin/meals/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="card mx-auto" style="max-width: 700px;">
    {{-- Header en el mismo púrpura que el botón “Guardar” --}}
    <div class="card-header bg-warning text-dark text-center">
      <h2 class="mb-0">Editar Comida</h2>
    </div>
    <div class="card-body">

      <form action="{{ route('admin.meals.update', $meal) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label for="date" class="form-label">Fecha</label>
            <input
              type="date"
              id="date"
              name="date"
              value="{{ old('date', $meal->date) }}"
              class="form-control @error('date') is-invalid @enderror"
            >
            @error('date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="time" class="form-label">Hora</label>
            <input
              type="time"
              id="time"
              name="time"
              value="{{ old('time', $meal->time) }}"
              class="form-control @error('time') is-invalid @enderror"
            >
            @error('time')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3 mt-4">
          <label for="meal_type" class="form-label">Tipo de Comida</label>
          <select
            id="meal_type"
            name="meal_type"
            class="form-select @error('meal_type') is-invalid @enderror"
          >
            <option value="" disabled {{ old('meal_type', $meal->meal_type) ? '' : 'selected' }}>Selecciona...</option>
            @foreach(['Desayuno','Almuerzo','Cena','Snack'] as $t)
              <option value="{{ $t }}" {{ old('meal_type', $meal->meal_type)==$t?'selected':'' }}>
                {{ $t }}
              </option>
            @endforeach
          </select>
          @error('meal_type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Descripción</label>
          <input
            type="text"
            id="description"
            name="description"
            value="{{ old('description', $meal->description) }}"
            placeholder="Ej: Ensalada de quinoa"
            class="form-control @error('description') is-invalid @enderror"
          >
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label">Ingredientes</label>
          <div id="ingredients-list" class="row g-2"></div>
          <button
            type="button"
            id="add-ingredient"
            class="btn btn-warning mt-2"
          >
            <i class="ri-add-line"></i> Añadir ingrediente
          </button>
          @error('ingredients')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end">
          <a href="{{ route('home') }}" class="btn btn-secondary me-2">Cancelar</a>
          <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
  const ingredients = @json($ingredients);
  const initial = @json(old('ingredients') ?? $meal->ingredients->map(fn($i)=>['id'=>$i->id,'quantity'=>$i->pivot->quantity])->toArray());
  const container = document.getElementById('ingredients-list');
  const btnAdd = document.getElementById('add-ingredient');

  function addRow(data={}) {
    const idx = container.children.length;
    const row = document.createElement('div');
    row.className = 'col-12 row g-2 align-items-end';

    // SELECT
    const colSel = document.createElement('div'); colSel.className='col-md-6';
    const sel = document.createElement('select');
    sel.name=`ingredients[${idx}][id]`;
    sel.className='form-select';
    sel.innerHTML=`<option disabled ${!data.id?'selected':''}>Elige ingrediente</option>`
      +ingredients.map(i=>`<option value="${i.id}" ${i.id==data.id?'selected':''}>${i.name}</option>`).join('');
    colSel.append(sel); row.append(colSel);

    // INPUT
    const colInp = document.createElement('div'); colInp.className='col-md-4';
    const inp = document.createElement('input');
    inp.type='number'; inp.step='0.01';
    inp.name=`ingredients[${idx}][quantity]`;
    inp.value=data.quantity||'';
    inp.placeholder='Cantidad (g)';
    inp.className='form-control'; colInp.append(inp); row.append(colInp);

    // BTN REMOVE
    const colBtn = document.createElement('div'); colBtn.className='col-md-2 d-grid';
    const btn = document.createElement('button');
    btn.type='button'; btn.className='btn btn-outline-danger';
    btn.innerHTML='<i class="ri-close-line"></i>';
    btn.onclick=()=>row.remove();
    colBtn.append(btn); row.append(colBtn);

    container.append(row);
  }

  btnAdd.addEventListener('click',()=>addRow());
  initial.forEach(d=>addRow(d));
})();
</script>
@endpush
