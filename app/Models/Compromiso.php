<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compromiso extends Model
{
    protected $table = 'compromisos';
    
    protected $fillable = [
        'acta_id',
        'descripcion',
        'acta_persona_id', // ✅ CORRECTO: usar acta_persona_id
        'fecha',
        'estado'
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'estado' => 'string'
    ];

    // Relaciones
    public function acta()
    {
        return $this->belongsTo(Acta::class);
    }

    // ✅ RELACIÓN DIRECTA CON ACTA_PERSONA
    public function actaPersona()
    {
        return $this->belongsTo(ActaPersona::class, 'acta_persona_id');
    }

    // ✅ RELACIÓN CON PERSONA A TRAVÉS DE ACTA_PERSONA
    public function persona()
    {
        return $this->hasOneThrough(
            Persona::class,
            ActaPersona::class,
            'id',           // Clave foránea en acta_persona
            'id',           // Clave foránea en personas  
            'acta_persona_id', // Clave local en compromisos
            'persona_id'    // Clave local en acta_persona
        );
    }

    // ✅ RELACIÓN CON EMPRESA A TRAVÉS DE ACTA_PERSONA
    public function empresa()
    {
        return $this->hasOneThrough(
            Empresa::class,
            ActaPersona::class,
            'id',           // Clave foránea en acta_persona
            'id',           // Clave foránea en empresas
            'acta_persona_id', // Clave local en compromisos
            'empresa_id'    // Clave local en acta_persona
        );
    }
}