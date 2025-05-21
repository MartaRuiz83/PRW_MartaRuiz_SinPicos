<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Ingredient;
use App\Models\Tip;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * 1) Mostrar listado de comidas (público /home).
     */
    public function index()
    {
        $meals = Meal::with('ingredients')
                     ->where('user_id', auth()->id())
                     ->orderByDesc('date')
                     ->orderByDesc('time')
                     ->get();

        $carbohydrates = 0;
        $proteins      = 0;
        $fats          = 0;
        $calories      = 0;

        foreach ($meals as $meal) {
            foreach ($meal->ingredients as $ingredient) {
                $quantity = $ingredient->pivot->quantity;
                $carbohydrates += ($ingredient->carbohydrates ?? 0) * $quantity / 100;
                $proteins      += ($ingredient->proteins      ?? 0) * $quantity / 100;
                $fats          += ($ingredient->fats          ?? 0) * $quantity / 100;
                $calories      += ($ingredient->calories      ?? 0) * $quantity / 100;
            }
        }

        $now = new \DateTimeImmutable('today');
        $dates = [
            'today'     => $now,
            'yesterday' => $now->modify('-1 day'),
            'tomorrow'  => null,
        ];

        $tips = Tip::where('showed', false)
                    ->where('user_id', auth()->id())
                    ->get();

        return view('home', compact(
            'meals',
            'carbohydrates',
            'proteins',
            'fats',
            'calories',
            'dates',
            'tips'
        ));
    }

    /**
     * 2) Muestra el formulario de creación de comida.
     */
    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.meals.create', compact('ingredients'));
    }

    /**
     * 3) Procesa el envío y guarda la comida con sus ingredientes.
     */
   public function store(Request $request)
{
    $data = $request->validate([
        'date'                   => ['required', 'date', 'before_or_equal:today'],
        'time'                   => 'required',
        'meal_type'              => 'required|string',
        'description'            => 'nullable|string',
        'ingredients'            => 'required|array|min:1',
        'ingredients.*.id'       => 'required|exists:ingredients,id',
        'ingredients.*.quantity' => 'required|numeric|min:0.1',
    ], [
        'date.required'                   => 'La fecha es obligatoria.',
        'date.date'                       => 'El formato de fecha debe ser YYYY-MM-DD.',
        'date.before_or_equal'           => 'No puedes registrar una comida para un día futuro.',
        'time.required'                   => 'La hora es obligatoria.',
        'meal_type.required'              => 'El tipo de comida es obligatorio.',
        'meal_type.string'                => 'El tipo de comida debe ser texto.',
        'description.string'              => 'La descripción debe ser texto.',
        'ingredients.required'            => 'Debes seleccionar al menos un ingrediente.',
        'ingredients.array'               => 'Los ingredientes deben enviarse como un arreglo.',
        'ingredients.min'                 => 'Debes seleccionar al menos :min ingrediente.',
        'ingredients.*.id.required'       => 'El ID del ingrediente es obligatorio.',
        'ingredients.*.id.exists'         => 'El ingrediente seleccionado no es válido.',
        'ingredients.*.quantity.required' => 'La cantidad es obligatoria para cada ingrediente.',
        'ingredients.*.quantity.numeric'  => 'La cantidad debe ser un número.',
        'ingredients.*.quantity.min'      => 'La cantidad mínima para un ingrediente es :min.',
    ]);

    $meal = Meal::create([
        'date'        => $data['date'],
        'time'        => $data['time'],
        'meal_type'   => $data['meal_type'],
        'description' => $data['description'],
        'user_id'     => auth()->id(),
    ]);

    $pivot = [];
    foreach ($data['ingredients'] as $ing) {
        $pivot[$ing['id']] = ['quantity' => $ing['quantity']];
    }
    $meal->ingredients()->sync($pivot);

    return redirect()
        ->route('home')
        ->with('success', 'Comida registrada correctamente.');
}


    /**
     * 4) Muestra el detalle de una comida (admin.meals.show).
     */
    public function show(Meal $meal)
    {
        $meal->load('ingredients');
        return view('admin.meals.show', compact('meal'));
    }

    /**
     * 5) Formulario de edición (admin.meals.edit).
     */
    public function edit(Meal $meal)
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $meal->load('ingredients');
        return view('admin.meals.edit', compact('meal', 'ingredients'));
    }

    /**
     * 6) Actualiza la comida en BD (admin.meals.update).
     */
    public function update(Request $request, Meal $meal)
    {
        $data = $request->validate([
            'date'                   => 'required|date',
            'time'                   => 'required',
            'meal_type'              => 'required|string',
            'description'            => 'nullable|string',
            'ingredients'            => 'required|array|min:1',
            'ingredients.*.id'       => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.1',
        ], [
            'date.required'                   => 'La fecha es obligatoria.',
            'date.date'                       => 'El formato de fecha debe ser YYYY-MM-DD.',
            'time.required'                   => 'La hora es obligatoria.',
            'meal_type.required'              => 'El tipo de comida es obligatorio.',
            'meal_type.string'                => 'El tipo de comida debe ser texto.',
            'description.string'              => 'La descripción debe ser texto.',
            'ingredients.required'            => 'Debes seleccionar al menos un ingrediente.',
            'ingredients.array'               => 'Los ingredientes deben enviarse como un arreglo.',
            'ingredients.min'                 => 'Debes seleccionar al menos :min ingrediente.',
            'ingredients.*.id.required'       => 'El ID del ingrediente es obligatorio.',
            'ingredients.*.id.exists'         => 'El ingrediente seleccionado no es válido.',
            'ingredients.*.quantity.required' => 'La cantidad es obligatoria para cada ingrediente.',
            'ingredients.*.quantity.numeric'  => 'La cantidad debe ser un número.',
            'ingredients.*.quantity.min'      => 'La cantidad mínima para un ingrediente es :min.',
        ]);

        $meal->update([
            'date'        => $data['date'],
            'time'        => $data['time'],
            'meal_type'   => $data['meal_type'],
            'description' => $data['description'],
        ]);

        $pivot = [];
        foreach ($data['ingredients'] as $ing) {
            $pivot[$ing['id']] = ['quantity' => $ing['quantity']];
        }
        $meal->ingredients()->sync($pivot);

        // Si editamos desde /home, recibiremos 'date' oculto
        if ($request->filled('date')) {
            return redirect()
                ->route('home', ['date' => $request->input('date')])
                ->with('success', 'Comida actualizada correctamente.');
        }

        // Si no, volvemos al admin
        return redirect()
            ->route('admin.meals.index')
            ->with('success', 'Comida actualizada correctamente.');
    }

    /**
     * 7) Elimina la comida (admin.meals.destroy).
     */
    public function destroy(Request $request, Meal $meal)
    {
        // Desvincula ingredientes y borra
        $meal->ingredients()->detach();
        $meal->delete();

        // Si venimos de la vista /home, recibiremos un hidden 'date'
        if ($request->filled('date')) {
            return redirect()
                ->route('home', ['date' => $request->input('date')])
                ->with('success', 'Comida eliminada correctamente.');
        }

        // Si no, redirige al listado de admin
        return redirect()
            ->route('admin.meals.index')
            ->with('success', 'Comida eliminada correctamente.');
    }
}
