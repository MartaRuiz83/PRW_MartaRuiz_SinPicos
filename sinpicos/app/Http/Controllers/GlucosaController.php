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
     *
     * @param  Request       $request
     * @param  string|null   $date  Fecha en formato Y-m-d (opcional)
     */
    public function index(Request $request, $date = null)
    {
        // 1) Fecha seleccionada (por defecto hoy)
        $date = $date
            ? Carbon::parse($date)->startOfDay()
            : Carbon::today();

        // 2) Mediciones de ese día para el usuario autenticado
        $glucosas = Glucosa::where('usuario_id', auth()->id())
            ->whereDate('fecha', $date->toDateString())
            ->orderBy('hora')
            ->get();

        // 3) Navegación de días
        $yesterday = $date->copy()->subDay()->toDateString();
        $tomorrow  = $date->copy()->addDay();
        $tomorrow  = $tomorrow->lte(Carbon::today())
            ? $tomorrow->toDateString()
            : null;

        return view('glucosa.index', compact(
            'glucosas', 'date', 'yesterday', 'tomorrow'
        ));
    }

    /**
     * Mostrar formulario para crear nueva medición.
     */
    public function create()
    {
        return view('glucosa.create');
    }

    /**
     * Almacenar nueva medición de glucosa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha'         => 'required|date',
            'hora'          => 'required',
            'momento'       => 'required|in:ANTES,DESPUÉS',
            'nivel_glucosa' => 'required|integer|min:0',
        ], [
            'fecha.required'        => 'La fecha es obligatoria.',
            'fecha.date'            => 'La fecha no tiene un formato válido (Y-m-d).',
            'hora.required'         => 'La hora es obligatoria.',
            'momento.required'      => 'El momento de la medición es obligatorio.',
            'momento.in'            => 'El momento debe ser "ANTES" o "DESPUÉS".',
            'nivel_glucosa.required'=> 'El nivel de glucosa es obligatorio.',
            'nivel_glucosa.integer' => 'El nivel de glucosa debe ser un número entero.',
            'nivel_glucosa.min'     => 'El nivel de glucosa no puede ser negativo.',
        ]);

        Glucosa::create([
            'usuario_id'    => auth()->id(),
            'fecha'         => $validated['fecha'],
            'hora'          => $validated['hora'],
            'momento'       => $validated['momento'],
            'nivel_glucosa' => $validated['nivel_glucosa'],
        ]);

        return redirect()
            ->route('glucosa.index', ['date' => now()->toDateString()])
            ->with('success', 'Medición añadida correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Glucosa $glucosa)
    {
        return view('glucosa.edit', compact('glucosa'));
    }

    /**
     * Actualizar la medición.
     */
    public function update(Request $request, Glucosa $glucosa)
    {
        $validated = $request->validate([
            'fecha'         => 'required|date',
            'hora'          => 'required',
            'momento'       => 'required|in:ANTES,DESPUÉS',
            'nivel_glucosa' => 'required|integer|min:0',
        ], [
            'fecha.required'        => 'La fecha es obligatoria.',
            'fecha.date'            => 'La fecha no tiene un formato válido (Y-m-d).',
            'hora.required'         => 'La hora es obligatoria.',
            'momento.required'      => 'El momento de la medición es obligatorio.',
            'momento.in'            => 'El momento debe ser "ANTES" o "DESPUÉS".',
            'nivel_glucosa.required'=> 'El nivel de glucosa es obligatorio.',
            'nivel_glucosa.integer' => 'El nivel de glucosa debe ser un número entero.',
            'nivel_glucosa.min'     => 'El nivel de glucosa no puede ser negativo.',
        ]);

        $glucosa->update($validated);

        return redirect()
            ->route('glucosa.index', ['date' => $validated['fecha']])
            ->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Eliminar una medición.
     */
    public function destroy(Glucosa $glucosa)
    {
        $fecha = $glucosa->fecha;
        $glucosa->delete();

        return redirect()
            ->route('glucosa.index', ['date' => $fecha])
            ->with('success', 'Registro borrado correctamente.');
    }
}
