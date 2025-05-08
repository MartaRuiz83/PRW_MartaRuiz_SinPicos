{{-- resources/views/meals/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
  <h1 class="text-2xl font-bold mb-6">Registrar Nueva Comida</h1>

  <form action="{{ route('meals.store') }}" method="POST"
        class="space-y-6 bg-white p-6 rounded-lg shadow">
    @csrf

    {{-- Fecha y Hora --}}
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium">Fecha</label>
        <input type="date" name="date" value="{{ old('date') }}"
               class="mt-1 w-full border-gray-200 rounded-lg @error('date') border-red-500 @enderror">
        @error('date') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
      </div>
      <div>
        <label class="block text-sm font-medium">Hora</label>
        <input type="time" name="time" value="{{ old('time') }}"
               class="mt-1 w-full border-gray-200 rounded-lg @error('time') border-red-500 @enderror">
        @error('time') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- Tipo de Comida --}}
    <div>
      <label class="block text-sm font-medium">Tipo de Comida</label>
      <select name="meal_type"
              class="mt-1 w-full border-gray-200 rounded-lg @error('meal_type') border-red-500 @enderror">
        <option value="" disabled {{ old('meal_type')?'':'selected' }}>Selecciona...</option>
        @foreach(['Desayuno','Almuerzo','Cena','Snack'] as $t)
          <option value="{{ $t }}" {{ old('meal_type')==$t?'selected':'' }}>
            {{ $t }}
          </option>
        @endforeach
      </select>
      @error('meal_type') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Descripción --}}
    <div>
      <label class="block text-sm font-medium">Descripción</label>
      <input type="text" name="description" value="{{ old('description') }}"
             placeholder="Ej: Ensalada de quinoa"
             class="mt-1 w-full border-gray-200 rounded-lg @error('description') border-red-500 @enderror">
      @error('description') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Ingredientes dinámicos --}}
    <div>
      <label class="block text-sm font-medium mb-1">Ingredientes</label>
      <div id="ingredients-list" class="space-y-2"></div>
      <button type="button" id="add-ingredient"
              class="mt-2 inline-flex items-center space-x-1 text-sm text-primary hover:underline">
        <i class="ri-add-line"></i><span>Añadir ingrediente</span>
      </button>
      @error('ingredients') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Botones --}}
    <div class="flex justify-end space-x-2">
      <a href="{{ route('home') }}"
         class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
        Cancelar
      </a>
      <button type="submit"
              class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
        Guardar
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  const ingredients = @json($ingredients);
  const container   = document.getElementById('ingredients-list');
  const btnAdd      = document.getElementById('add-ingredient');

  function addRow(old = {}) {
    const idx = container.children.length;
    const div = document.createElement('div');
    div.className = 'grid grid-cols-3 gap-2 items-end';

    // Select ingrediente
    const sel = document.createElement('select');
    sel.name = `ingredients[${idx}][id]`;
    sel.className = 'border-gray-200 rounded-lg';
    sel.innerHTML = `<option value="" disabled ${!old.id?'selected':''}>Elige ingrediente</option>` +
      ingredients.map(i =>
        `<option value="${i.id}" ${old.id==i.id?'selected':''}>${i.name}</option>`
      ).join('');
    div.append(sel);

    // Input cantidad
    const inp = document.createElement('input');
    inp.type = 'number';
    inp.step = '0.01';
    inp.name = `ingredients[${idx}][quantity]`;
    inp.value = old.quantity||'';
    inp.placeholder = 'Cantidad (g)';
    inp.className = 'border-gray-200 rounded-lg';
    div.append(inp);

    // Botón eliminar
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.innerHTML = '<i class="ri-close-line"></i>';
    btn.className = 'text-red-500 hover:text-red-700';
    btn.addEventListener('click', ()=> div.remove());
    div.append(btn);

    container.append(div);
  }

  btnAdd.addEventListener('click', ()=> addRow());

  @if(old('ingredients'))
    @foreach(old('ingredients') as $ing)
      addRow({ id: '{{ $ing["id"] }}', quantity: '{{ $ing["quantity"] }}' });
    @endforeach
  @endif
</script>
@endpush
