<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudad extends Model
{
    use SoftDeletes;
    
    protected $table = 'ciudades';
    
    protected $fillable = [
        'nombre',
        'pais_id'
    ];
    
    protected $casts = [
        'nombre' => 'string'
    ];

    // Relaciones
    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }

    public function actas()
    {
        return $this->hasMany(Acta::class);
    }

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }
}