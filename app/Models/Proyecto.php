<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use SoftDeletes;
    
    protected $table = 'proyectos';
    
    protected $fillable = [
        'nombre',
        'empresa_id'
    ];
    
    protected $casts = [
        'nombre' => 'string'
    ];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function actas()
    {
        return $this->hasMany(Acta::class);
    }
}