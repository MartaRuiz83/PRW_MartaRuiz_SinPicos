<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Glucosa extends Model
{
    use HasFactory;

    protected $table = 'glucosa';

    protected $fillable = [
        'usuario_id',
        'fecha',
        'hora',
        'momento',
        'nivel_glucosa',
    ];

    // Relación: Una medición pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
