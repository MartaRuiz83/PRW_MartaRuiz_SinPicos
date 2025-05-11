<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recomendation;
use Illuminate\Http\Request;

class RecomendationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Obtener todas las recomendaciones paginadas
        $recs = Recomendation::orderBy('id', 'asc')
                             ->paginate(10);

        return view('admin.recomendations.index', compact('recs'));
    }

    public function create()
    {
        return view('admin.recomendations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        // Crear nueva recomendación sin asignar user_id
        Recomendation::create($validated);

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación creada correctamente.');
    }

    public function show(Recomendation $recomendation)
    {
        // Mostrar detalle de recomendación
        return view('admin.recomendations.show', compact('recomendation'));
    }

    public function edit(Recomendation $recomendation)
    {
        // Mostrar formulario de edición
        return view('admin.recomendations.edit', compact('recomendation'));
    }

    public function update(Request $request, Recomendation $recomendation)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $recomendation->update($validated);

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación actualizada correctamente.');
    }

    public function destroy(Recomendation $recomendation)
    {
        $recomendation->delete();

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación eliminada correctamente.');
    }
}
