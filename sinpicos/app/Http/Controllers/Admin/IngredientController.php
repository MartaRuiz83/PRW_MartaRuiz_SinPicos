<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\ControlAdmin;

class IngredientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(ControlAdmin::class); // Middleware para verificar rol
    }

    /**
     * Mostrar listado de ingredientes (para DataTables cliente).
     */
    public function index()
    {
        // Traemos **todos** los ingredientes
        $ingredients = Ingredient::orderBy('id', 'asc')->get();

        return view('admin.ingredients.index', compact('ingredients'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.ingredients.create');
    }

    /**
     * Almacenar un ingrediente nuevo.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'carbohydrates' => ['nullable', 'numeric', 'min:0'],
            'proteins'      => ['nullable', 'numeric', 'min:0'],
            'fats'          => ['nullable', 'numeric', 'min:0'],
            'calories'      => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required'           => 'El nombre del ingrediente es obligatorio.',
            'name.string'             => 'El nombre debe ser texto.',
            'name.max'                => 'El nombre no puede exceder los :max caracteres.',

            'carbohydrates.numeric'   => 'Los carbohidratos deben ser un número.',
            'carbohydrates.min'       => 'Los carbohidratos no pueden ser negativos.',

            'proteins.numeric'        => 'Las proteínas deben ser un número.',
            'proteins.min'            => 'Las proteínas no pueden ser negativas.',

            'fats.numeric'            => 'Las grasas deben ser un número.',
            'fats.min'                => 'Las grasas no pueden ser negativas.',

            'calories.integer'        => 'Las calorías deben ser un número entero.',
            'calories.min'            => 'Las calorías no pueden ser negativas.',
        ]);

        Ingredient::create($data);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingrediente creado correctamente.');
    }

    /**
     * Mostrar detalle de un ingrediente.
     */
    public function show(Ingredient $ingredient)
    {
        return view('admin.ingredients.show', compact('ingredient'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Ingredient $ingredient)
    {
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    /**
     * Actualizar un ingrediente existente.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'carbohydrates' => ['nullable', 'numeric', 'min:0'],
            'proteins'      => ['nullable', 'numeric', 'min:0'],
            'fats'          => ['nullable', 'numeric', 'min:0'],
            'calories'      => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required'           => 'El nombre del ingrediente es obligatorio.',
            'name.string'             => 'El nombre debe ser texto.',
            'name.max'                => 'El nombre no puede exceder los :max caracteres.',

            'carbohydrates.numeric'   => 'Los carbohidratos deben ser un número.',
            'carbohydrates.min'       => 'Los carbohidratos no pueden ser negativos.',

            'proteins.numeric'        => 'Las proteínas deben ser un número.',
            'proteins.min'            => 'Las proteínas no pueden ser negativas.',

            'fats.numeric'            => 'Las grasas deben ser un número.',
            'fats.min'                => 'Las grasas no pueden ser negativas.',

            'calories.integer'        => 'Las calorías deben ser un número entero.',
            'calories.min'            => 'Las calorías no pueden ser negativas.',
        ]);

        $ingredient->update($data);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingrediente actualizado correctamente.');
    }

    /**
     * Eliminar un ingrediente.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingrediente eliminado correctamente.');
    }
}
