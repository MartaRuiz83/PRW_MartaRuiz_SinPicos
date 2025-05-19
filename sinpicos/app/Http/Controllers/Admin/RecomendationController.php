<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Recomendation;
use App\Http\Controllers\Controller;
use App\Http\Middleware\ControlAdmin;

class RecomendationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(ControlAdmin::class); // Middleware para verificar rol
    }

    /**
     * Mostrar listado de recomendaciones, paginadas.
     */
    public function index()
    {
        // Traemos todos para que DataTables procese en el cliente
        $recs = Recomendation::orderBy('id', 'asc')->get();

        return view('admin.recomendations.index', compact('recs'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('admin.recomendations.create');
    }

    /**
     * Validar y almacenar nueva recomendación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
        ], [
            'titulo.required'      => 'El título es obligatorio.',
            'titulo.string'        => 'El título debe ser texto.',
            'titulo.max'           => 'El título no puede exceder los :max caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string'   => 'La descripción debe ser texto.',
        ]);

        Recomendation::create($validated);

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación creada correctamente.');
    }

    /**
     * Mostrar detalle de una recomendación.
     */
    public function show(Recomendation $recomendation)
    {
        return view('admin.recomendations.show', compact('recomendation'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Recomendation $recomendation)
    {
        return view('admin.recomendations.edit', compact('recomendation'));
    }

    /**
     * Validar y actualizar recomendación existente.
     */
    public function update(Request $request, Recomendation $recomendation)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
        ], [
            'titulo.required'      => 'El título es obligatorio.',
            'titulo.string'        => 'El título debe ser texto.',
            'titulo.max'           => 'El título no puede exceder los :max caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string'   => 'La descripción debe ser texto.',
        ]);

        $recomendation->update($validated);

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación actualizada correctamente.');
    }

    /**
     * Eliminar una recomendación.
     */
    public function destroy(Recomendation $recomendation)
    {
        $recomendation->delete();

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación eliminada correctamente.');
    }
}
