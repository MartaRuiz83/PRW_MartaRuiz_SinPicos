<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'carbohydrates',
        'proteins',
        'fats',
        'calories',
    ];

    // Si quieres, puedes definir casts para que siempre lleguen en el tipo adecuado
    protected $casts = [
        'carbohydrates' => 'float',
        'proteins'      => 'float',
        'fats'          => 'float',
        'calories'      => 'integer',
    ];

}
