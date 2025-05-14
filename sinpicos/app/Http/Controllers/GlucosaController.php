<?php

namespace App\Http\Controllers;

use App\Models\Glucosa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GlucosaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el listado de mediciones filtradas por fecha.
     * @param  Request $request
     * @param  string|null $date  Fecha en formato Y-m-d (opcional)
     */
    public function index(Request $request, $date = null)
    {
        // 1) Definimos la fecha seleccionada (por defecto hoy)
        $date = $date
            ? Carbon::parse($date)->startOfDay()
            : Carbon::today();

        // 2) Recuperamos sólo las mediciones de ese día
        $glucosas = Glucosa::where('usuario_id', auth()->id())
            ->whereDate('fecha', $date->toDateString())
            ->orderBy('hora')
            ->get();

        // 3) Preparamos navegación de días
        $yesterday = $date->copy()->subDay()->toDateString();
        $tomorrow  = $date->copy()->addDay();
        // Sólo permitimos "mañana" hasta hoy
        $tomorrow = $tomorrow->lte(Carbon::today())
            ? $tomorrow->toDateString()
            : null;

        return view('glucosa.index', compact(
            'glucosas',
            'date',
            'yesterday',
            'tomorrow'
        ));
    }

    public function create()
    {
        return view('glucosa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'         => 'required|date',
            'hora'          => 'required',
            'momento'       => 'required|in:ANTES,DESPUÉS',
            'nivel_glucosa' => 'required|integer|min:0',
        ]);

        Glucosa::create([
            'usuario_id'    => auth()->id(),
            'fecha'         => $request->fecha,
            'hora'          => $request->hora,
            'momento'       => $request->momento,
            'nivel_glucosa' => $request->nivel_glucosa,
        ]);

        return redirect()
            ->route('glucosa.index', ['date' => now()->toDateString()])
            ->with('success', 'Medición añadida correctamente.');
    }

    public function edit(Glucosa $glucosa)
    {
        $this->authorize('update', $glucosa);
        return view('glucosa.edit', compact('glucosa'));
    }

    public function update(Request $request, Glucosa $glucosa)
    {
        $this->authorize('update', $glucosa);

        $request->validate([
            'fecha'         => 'required|date',
            'hora'          => 'required',
            'momento'       => 'required|in:ANTES,DESPUÉS',
            'nivel_glucosa' => 'required|integer|min:0',
        ]);

        $glucosa->update($request->only(
            'fecha','hora','momento','nivel_glucosa'
        ));

        return redirect()
            ->route('glucosa.index', ['date' => $glucosa->fecha])
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy(Glucosa $glucosa)
    {
        $this->authorize('delete', $glucosa);
        $fecha = $glucosa->fecha;
        $glucosa->delete();

        return redirect()
            ->route('glucosa.index', ['date' => $fecha])
            ->with('success', 'Registro borrado correctamente.');
    }
}
