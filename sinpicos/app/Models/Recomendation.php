<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recomendation extends Model
{
    // Laravel infiere la tabla 'recomendations'
    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
