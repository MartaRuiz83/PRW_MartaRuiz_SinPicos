<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * 1) Mostrar listado de comidas (público /home).
     */
    public function index()
    {
        // cargamos los ingredientes para calcular macros si quieres
        $meals = Meal::with('ingredients')
                     ->orderByDesc('date')
                     ->orderByDesc('time')
                     ->get();

        return view('home', compact('meals'));
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
            'date'                   => 'required|date',
            'time'                   => 'required',
            'meal_type'              => 'required|string',
            'description'            => 'required|string',
            'ingredients'            => 'required|array|min:1',
            'ingredients.*.id'       => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.1',
        ]);

        $meal = Meal::create([
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

        return redirect()->route('home')
                         ->with('success', 'Comida registrada correctamente');
    }

    /**
     * 4) Muestra el detalle de una comida (admin.meals.show).
     */
    public function show(Meal $meal)
    {
        // cargamos ingredientes y cantidades
        $meal->load('ingredients');
        return view('admin.meals.show', compact('meal'));
    }

    /**
     * 5) Formulario de edición (admin.meals.edit).
     */
    public function edit(Meal $meal)
    {
        $ingredients     = Ingredient::orderBy('name')->get();
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
            'description'            => 'required|string',
            'ingredients'            => 'required|array|min:1',
            'ingredients.*.id'       => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.1',
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

        return redirect()->route('admin.meals.index')
                         ->with('success', 'Comida actualizada correctamente');
    }

    /**
     * 7) Elimina la comida (admin.meals.destroy).
     */
    public function destroy(Meal $meal)
    {
        $meal->ingredients()->detach();
        $meal->delete();

        return redirect()->route('admin.meals.index')
                         ->with('success', 'Comida eliminada correctamente');
    }
}
