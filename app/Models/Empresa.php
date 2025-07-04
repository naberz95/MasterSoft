<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;
    
    protected $table = 'empresas';
    
    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'logo_empresa',
        'telefono',
        'email'
    ];
    
    protected $casts = [
        'nit' => 'string',
        'nombre' => 'string',
        'direccion' => 'string',
        'logo_empresa' => 'string',
        'telefono' => 'string',
        'email' => 'string'
    ];

    // Relaciones
    public function personas()
    {
        return $this->hasMany(Persona::class);
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    public function actas()
    {
        return $this->hasMany(Acta::class);
    }

    // Actas donde esta empresa tiene personas participando
    public function actaPersonas()
    {
        return $this->hasMany(ActaPersona::class);
    }

    // Personas que participan en actas en representación de esta empresa
    public function participacionesEnActas()
    {
        return $this->belongsToMany(Persona::class, 'acta_persona')
                    ->withPivot('acta_id', 'asistio')
                    ->withTimestamps();
    }
}