<?php

namespace App\Http\Controllers;

use App\Models\Recomendation;

class PublicRecomendationController extends Controller
{
    public function index()
    {
        $recs = Recomendation::orderBy('created_at','desc')->get();
        return view('recomendations', compact('recs'));
    }
}
