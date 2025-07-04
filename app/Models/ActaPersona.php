<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActaPersona extends Model
{
    protected $table = 'acta_persona';
    
    protected $fillable = [
        'acta_id',
        'persona_id',
        'empresa_id',
        'asistio'
    ];
    
    protected $casts = [
        'asistio' => 'boolean'
    ];

    // Relaciones
    public function acta()
    {
        return $this->belongsTo(Acta::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Compromisos asignados a esta persona en esta acta específica
    public function compromisos()
    {
        return $this->hasMany(Compromiso::class, 'acta_persona_id');
    }
}