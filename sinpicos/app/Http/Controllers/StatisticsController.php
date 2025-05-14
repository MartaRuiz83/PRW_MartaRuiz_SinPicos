<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Meal;
use App\Models\Glucosa;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1) Rango de fechas: últimos 7 días
        $startDate = Carbon::today()->subDays(6);
        $endDate   = Carbon::today();

        // 2) Traer comidas con ingredientes (fijarse en user_id, no usuario_id)
        $meals = Meal::with('ingredients')
            ->where('user_id', $user->id)
            ->whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->get();

        // 3) Acumular macros por fecha
        $daily = [];
        foreach ($meals as $meal) {
            $d = $meal->date;
            if (!isset($daily[$d])) {
                $daily[$d] = ['carbs'=>0,'proteins'=>0,'fats'=>0,'calories'=>0];
            }
            foreach ($meal->ingredients as $ing) {
                $q = $ing->pivot->quantity;
                $daily[$d]['carbs']    += ($ing->carbohydrates ?? 0) * $q/100;
                $daily[$d]['proteins'] += ($ing->proteins      ?? 0) * $q/100;
                $daily[$d]['fats']     += ($ing->fats          ?? 0) * $q/100;
                $daily[$d]['calories'] += ($ing->calories      ?? 0) * $q/100;
            }
        }
        ksort($daily);

        // 4) Preparar datos de macros para la vista
        $labels   = array_values(array_keys($daily));
        $carbs    = array_map(fn($v)=>round($v['carbs'],1),   array_values($daily));
        $proteins = array_map(fn($v)=>round($v['proteins'],1),array_values($daily));
        $fats     = array_map(fn($v)=>round($v['fats'],1),    array_values($daily));
        $calories = array_map(fn($v)=>round($v['calories'],1),array_values($daily));

        // Totales acumulados
        $totalCarbs    = array_sum($carbs);
        $totalProteins = array_sum($proteins);
        $totalFats     = array_sum($fats);
        $totalCalories = array_sum($calories);

        // 5) Traer glucosa en el mismo rango
        $glucosas = Glucosa::where('usuario_id', $user->id)
            ->whereBetween('fecha', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->get();

        // Agrupar por fecha y calcular media diaria
        $dailyGlucose = [];
        foreach ($glucosas as $g) {
            $d = $g->fecha;
            $dailyGlucose[$d][] = $g->nivel_glucosa;
        }
        ksort($dailyGlucose);

        $glucoseValues = [];
        foreach ($labels as $d) {
            if (isset($dailyGlucose[$d])) {
                $vals = $dailyGlucose[$d];
                $glucoseValues[] = round(array_sum($vals)/count($vals),1);
            } else {
                $glucoseValues[] = 0;
            }
        }

        // Resumen general de glucosa
        $all = $glucosas->pluck('nivel_glucosa');
        $avgGlucose = $all->avg() ?: 0;
        $minGlucose = $all->min() ?: 0;
        $maxGlucose = $all->max() ?: 0;

        // 6) Retornar vista con todos los datos
        return view('statistics', compact(
            'labels','carbs','proteins','fats','calories',
            'totalCarbs','totalProteins','totalFats','totalCalories',
            'startDate','endDate',
            'glucoseValues','avgGlucose','minGlucose','maxGlucose'
        ));
    }
}
