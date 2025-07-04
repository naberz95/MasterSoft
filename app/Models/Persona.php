<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    
    protected $table = 'personas';
    
    protected $fillable = [
        'nombre',
        'iniciales',
        'cargo',
        'firma_path',
        'tarjeta_profesional',
        'fecha_tarjeta',
        'cedula',
        'fecha_expedicion_cedula',
        'lugar_expedicion_cedula',
        'empresa_id'
    ];
    
    protected $casts = [
        'nombre' => 'string',
        'iniciales' => 'string',
        'cargo' => 'string',
        'firma_path' => 'string',
        'tarjeta_profesional' => 'string',
        'fecha_tarjeta' => 'date',
        'cedula' => 'string',
        'fecha_expedicion_cedula' => 'date',
        'lugar_expedicion_cedula' => 'string'
    ];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Actas donde esta persona es firmante de empresa
    public function actasComoFirmanteEmpresa()
    {
        return $this->hasMany(Acta::class, 'firmante_empresa_id');
    }

    // Actas donde esta persona es firmante GP
    public function actasComoFirmanteGp()
    {
        return $this->hasMany(Acta::class, 'firmante_gp_id');
    }

    // Relación many-to-many con Acta a través de ActaPersona
    public function actas()
    {
        return $this->belongsToMany(Acta::class, 'acta_persona')
                    ->withPivot('empresa_id', 'asistio')
                    ->withTimestamps();
    }

    // Relación directa con ActaPersona
    public function actaPersonas()
    {
        return $this->hasMany(ActaPersona::class);
    }

    // Compromisos asignados a esta persona (a través de ActaPersona)
    public function compromisos()
    {
        return $this->hasManyThrough(
            Compromiso::class,
            ActaPersona::class,
            'persona_id', // Clave foránea en acta_persona
            'acta_persona_id', // Clave foránea en compromisos
            'id', // Clave local en personas
            'id' // Clave local en acta_persona
        );
    }
}