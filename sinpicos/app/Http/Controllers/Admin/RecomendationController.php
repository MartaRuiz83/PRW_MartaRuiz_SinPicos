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

    /**
     * Mostrar listado de recomendaciones, paginadas.
     */
   // app/Http/Controllers/Admin/RecomendationController.php

public function index()
{
    // Antes: paginate(10)
    // $recs = Recomendation::orderBy('id', 'asc')->paginate(10);

    // Ahora: traemos TODOS para que DataTables los procese en el cliente
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
