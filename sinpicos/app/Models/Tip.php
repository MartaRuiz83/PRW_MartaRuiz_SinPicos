<?php

namespace App\Models;

use App\Models\User;
use App\Models\Recomendation;
use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
     protected $fillable = ['showed', 'user_id', 'recomendation_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recomendation()
    {
        return $this->belongsTo(Recomendation::class);
    }
}
