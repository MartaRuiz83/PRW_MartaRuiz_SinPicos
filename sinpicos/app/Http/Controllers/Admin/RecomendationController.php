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
        $recs = auth()->user()
                     ->recomendations()
                     ->orderBy('id', 'asc')
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

        auth()->user()
             ->recomendations()
             ->create($validated);

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación creada correctamente.');
    }

    /**
     * Muestra el detalle de una recomendación.
     */
    public function show(Recomendation $recomendation)
    {
        // Sólo el dueño puede verla
        if (auth()->id() !== $recomendation->user_id) {
            abort(403, 'No tienes permiso para ver esta recomendación.');
        }

        return view('admin.recomendations.show', compact('recomendation'));
    }

    public function edit(Recomendation $recomendation)
    {
        // Sólo el dueño puede editarla
        if (auth()->id() !== $recomendation->user_id) {
            abort(403, 'No tienes permiso para editar esta recomendación.');
        }

        return view('admin.recomendations.edit', compact('recomendation'));
    }

    public function update(Request $request, Recomendation $recomendation)
    {
        // Sólo el dueño puede actualizarla
        if (auth()->id() !== $recomendation->user_id) {
            abort(403, 'No tienes permiso para actualizar esta recomendación.');
        }

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
        // Sólo el dueño puede borrarla
        if (auth()->id() !== $recomendation->user_id) {
            abort(403, 'No tienes permiso para eliminar esta recomendación.');
        }

        $recomendation->delete();

        return redirect()
            ->route('admin.recomendations.index')
            ->with('success', 'Recomendación eliminada correctamente.');
    }
}
