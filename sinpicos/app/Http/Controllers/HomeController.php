<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use DateTimeImmutable;

class HomeController extends Controller
{
    public function index($date=null)
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
    
    foreach ($meals as $meal) {
        foreach ($meal->ingredients as $ingredient) {
            $carbohydrates += $ingredient->carbohydrates * $ingredient->pivot->quantity/100;
            $proteins += $ingredient->proteins * $ingredient->pivot->quantity/100;
            $fats += $ingredient->fats * $ingredient->pivot->quantity/100;
    }
}

    $dates = [
        'today' => $date,
        'yesterday' => $date->modify('-1 day'),
        'tomorrow' =>($date < $now)? $date->modify("+1 day"):null,
    ];

    return view('home', compact('meals', 'carbohydrates', 'proteins', 'fats','dates'));
}

}
