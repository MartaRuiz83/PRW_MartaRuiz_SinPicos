<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{
    $meals = Meal::with('ingredients') 
                 ->orderByDesc('date')
                 ->orderByDesc('time')
                 ->get();

    return view('home', compact('meals'));
}

}
