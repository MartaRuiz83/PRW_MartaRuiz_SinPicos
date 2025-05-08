<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Mostrar listado de ingredientes.
     */
    public function index()
    {
        // Obtiene 10 por página (puedes cambiar el número)
        $ingredients = Ingredient::orderBy('id', 'desc')
                                 ->paginate(10);

        // Pasa la colección a la vista
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
        // Validación
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'carbohydrates' => ['nullable', 'numeric', 'min:0'],
            'proteins'      => ['nullable', 'numeric', 'min:0'],
            'fats'          => ['nullable', 'numeric', 'min:0'],
            'calories'      => ['nullable', 'integer', 'min:0'],
        ]);

        // Creación
        Ingredient::create($data);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingrediente creado correctamente.');
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
