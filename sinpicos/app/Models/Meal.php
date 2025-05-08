<?php

// app/Models/Meal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = ['date','time','meal_type','description'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->withPivot('quantity');
    }
}
