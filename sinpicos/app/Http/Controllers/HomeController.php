<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\Meal;
use DateTimeImmutable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index($date = null)
    {
        // Fecha de hoy
        $now = new DateTimeImmutable('today');

        // Si nos pasan una fecha, la parseamos; si no, usamos hoy
        if ($date) {
            $date = new DateTimeImmutable($date);
        } else {
            $date = $now;
        }

        // Obtenemos las comidas del usuario para esa fecha,
        // ordenadas primero por tipo de comida (Desayuno, Almuerzo, Snack, Cena)
        // y luego por hora ascendente
        $meals = Meal::with('ingredients')
            ->where('user_id', auth()->id())
            ->whereDate('date', $date->format('Y-m-d'))
            ->orderByRaw("
                FIELD(
                    meal_type,
                    'Desayuno',
                    'Almuerzo',
                    'Snack',
                    'Cena'
                )
            ")
            ->orderBy('time')
            ->get();

        // Inicializamos totales de macronutrientes y calorías
        $carbohydrates = 0;
        $proteins      = 0;
        $fats          = 0;
        $calories      = 0;

        // Recorremos cada ingrediente de cada comida para sumar
        foreach ($meals as $meal) {
            foreach ($meal->ingredients as $ingredient) {
                $quantity = $ingredient->pivot->quantity;

                $carbohydrates += ($ingredient->carbohydrates ?? 0) * $quantity / 100;
                $proteins      += ($ingredient->proteins      ?? 0) * $quantity / 100;
                $fats          += ($ingredient->fats          ?? 0) * $quantity / 100;
                $calories      += ($ingredient->calories      ?? 0) * $quantity / 100;
            }
        }

        // Preparamos fechas para navegación
        $dates = [
            'today'     => $date,
            'yesterday' => $date->modify('-1 day'),
            'tomorrow'  => ($date < $now) ? $date->modify('+1 day') : null,
        ];

        // Consejos pendientes
        $tips = Tip::where('showed', false)
            ->where('user_id', auth()->id())
            ->get();

        // Enviamos todo a la vista
        return view('home', [
            'meals'         => $meals,
            'carbohydrates' => $carbohydrates,
            'proteins'      => $proteins,
            'fats'          => $fats,
            'calories'      => $calories,
            'dates'         => $dates,
            'tips'          => $tips,
        ]);
    }
}
