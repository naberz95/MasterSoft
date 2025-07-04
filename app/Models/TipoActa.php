<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoActa extends Model
{
    use SoftDeletes;
    
    protected $table = 'tipos_acta';
    
    protected $fillable = [
        'nombre'
    ];
    
    protected $casts = [
        'nombre' => 'string'
    ];

    // Relación inversa con Acta
    public function actas()
    {
        return $this->hasMany(Acta::class, 'tipo_id');
    }
}