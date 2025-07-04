<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resumen extends Model
{
    protected $table = 'resumenes';
    
    protected $fillable = [
        'acta_id',
        'fecha',
        'descripcion',
        'horas',
        'facturable'
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'horas' => 'decimal:2',
        'facturable' => 'boolean'
    ];

    // Relaciones
    public function acta()
    {
        return $this->belongsTo(Acta::class);
    }
}