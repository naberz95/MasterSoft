<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use SoftDeletes;
    
    protected $table = 'paises';
    
    protected $fillable = [
        'nombre'
    ];
    
    protected $casts = [
        'nombre' => 'string'
    ];

    // Relaciones
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class);
    }
}