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
