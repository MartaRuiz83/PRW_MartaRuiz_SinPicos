<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{

   $meals = Meal::with('ingredients')
    ->where('user_id', auth()->id()) 
    ->whereDate('date', now()->format('Y-m-d'))
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

    return view('home', compact('meals', 'carbohydrates', 'proteins', 'fats'));
}

}
