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
        $now = new DateTimeImmutable('today');

        if ($date) {
            $date = new DateTimeImmutable($date);
        } else {
            $date = $now;
        }

        $meals = Meal::with('ingredients')
            ->where('user_id', auth()->id())
            ->whereDate('date', $date->format('Y-m-d'))
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        $carbohydrates = 0;
        $proteins = 0;
        $fats = 0;
        $calories = 0; // Nueva variable para calorías

        foreach ($meals as $meal) {
            foreach ($meal->ingredients as $ingredient) {
                $quantity = $ingredient->pivot->quantity;

                $carbohydrates += ($ingredient->carbohydrates ?? 0) * $quantity / 100;
                $proteins      += ($ingredient->proteins ?? 0) * $quantity / 100;
                $fats          += ($ingredient->fats ?? 0) * $quantity / 100;
                $calories      += ($ingredient->calories ?? 0) * $quantity / 100; // Cálculo de calorías
            }
        }

        $dates = [
            'today'     => $date,
            'yesterday' => $date->modify('-1 day'),
            'tomorrow'  => ($date < $now) ? $date->modify('+1 day') : null,
        ];

        $tips = Tip::where('showed', false)
            ->where('user_id', auth()->id())
            ->get();

        // Añadimos $calories a la vista
        return view('home', compact('meals', 'carbohydrates', 'proteins', 'fats', 'calories', 'dates', 'tips'));
    }
}
