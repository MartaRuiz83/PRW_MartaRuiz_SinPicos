<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Meal;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1) Defino el rango de fechas: últimos 7 días
        $startDate = Carbon::today()->subDays(6);
        $endDate   = Carbon::today();

        // 2) Traigo las comidas del usuario en ese rango
        $meals = Meal::with('ingredients')
            ->where('user_id', $user->id)
            ->whereBetween('date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->get();

        // 3) Inicializo $daily y sumo macros por fecha
        $daily = [];               // ← ¡Muy importante!
        foreach ($meals as $meal) {
            $d = $meal->date;
            if (!isset($daily[$d])) {
                $daily[$d] = [
                    'carbs'    => 0,
                    'proteins' => 0,
                    'fats'     => 0,
                    'calories' => 0,
                ];
            }
            foreach ($meal->ingredients as $ing) {
                $q = $ing->pivot->quantity;
                $daily[$d]['carbs']    += ($ing->carbohydrates ?? 0) * $q / 100;
                $daily[$d]['proteins'] += ($ing->proteins      ?? 0) * $q / 100;
                $daily[$d]['fats']     += ($ing->fats          ?? 0) * $q / 100;
                $daily[$d]['calories'] += ($ing->calories      ?? 0) * $q / 100;
            }
        }

        // 4) Ordeno por fecha
        ksort($daily);

        // 5) Preparo arrays para pasar a la vista (ECharts necesita arrays indexados 0,1,2…)
        $labels   = array_values(array_keys($daily));
        $carbs    = array_values(array_map(fn($v) => round($v['carbs'],   1), $daily));
        $proteins = array_values(array_map(fn($v) => round($v['proteins'],1), $daily));
        $fats     = array_values(array_map(fn($v) => round($v['fats'],    1), $daily));
        $calories = array_values(array_map(fn($v) => round($v['calories'],1), $daily));

        // 6) Totales acumulados
        $totalCarbs    = array_sum($carbs);
        $totalProteins = array_sum($proteins);
        $totalFats     = array_sum($fats);
        $totalCalories = array_sum($calories);

        // 7) Envío todo a la vista
        return view('statistics', compact(
            'labels','carbs','proteins','fats','calories',
            'totalCarbs','totalProteins','totalFats','totalCalories',
            'startDate','endDate'
        ));
    }
}
