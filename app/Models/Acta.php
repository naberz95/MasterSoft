<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acta extends Model
{
    use SoftDeletes;
    
    protected $table = 'actas';
    
    protected $fillable = [
        'tipo_id',
        'numero',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'lugar',
        'ciudad_id',
        'empresa_id',
        'proyecto_id',
        'objetivo',
        'agenda',
        'desarrollo',
        'conclusiones',
        'proxima_reunion',
        'firmante_empresa_id',
        'firmante_gp_id',
        'facturable',
        'version_id' // ✅ Para el sistema de versiones
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'proxima_reunion' => 'date',
        'facturable' => 'boolean',
        'objetivo' => 'string',
        'agenda' => 'string',
        'desarrollo' => 'string',
        'conclusiones' => 'string'
    ];

    // ✅ RELACIONES BÁSICAS
    public function tipoActa()
    {
        return $this->belongsTo(TipoActa::class, 'tipo_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function firmanteEmpresa()
    {
        return $this->belongsTo(Persona::class, 'firmante_empresa_id');
    }

    public function firmanteGp()
    {
        return $this->belongsTo(Persona::class, 'firmante_gp_id');
    }

    // ✅ RELACIONES CON PERSONAS (ASISTENTES)
    // Relación many-to-many con Persona a través de ActaPersona
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'acta_persona')
                    ->withPivot('empresa_id', 'asistio')
                    ->withTimestamps();
    }

    // Relación directa con ActaPersona (tabla intermedia)
    public function actaPersonas()
    {
        return $this->hasMany(ActaPersona::class);
    }

    // ✅ RELACIONES CON CONTENIDO DEL ACTA
    // Compromisos relacionados con esta acta
    public function compromisos()
    {
        return $this->hasMany(Compromiso::class);
    }

    // Resúmenes relacionados con esta acta
    public function resumenes()
    {
        return $this->hasMany(Resumen::class);
    }

    // ✅ RELACIÓN CON VERSIONES
    public function version()
    {
        return $this->belongsTo(Version::class);
    }

    // ✅ MÉTODOS AUXILIARES PARA VERSIONES
    public function tieneVersion()
    {
        return !is_null($this->version_id);
    }

    public function getInfoVersionAttribute()
    {
        if (!$this->version) {
            return 'Sin versión asignada';
        }

        return "Versión {$this->version->version} - {$this->version->descripcion_cambio}"; // ✅ Corrección: descripcion_cambio
    }

    // ✅ MÉTODOS AUXILIARES ADICIONALES
    
    /**
     * Obtener el número completo formateado del acta
     */
    public function getNumeroCompletoAttribute()
    {
        return "Acta N° {$this->numero}";
    }

    /**
     * Obtener la fecha formateada en español
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : 'Sin fecha';
    }

    /**
     * Obtener el resumen de duración de la reunión
     */
    public function getDuracionReunionAttribute()
    {
        if (!$this->hora_inicio || !$this->hora_fin) {
            return 'No definida';
        }
        
        $inicio = \Carbon\Carbon::createFromFormat('H:i', $this->hora_inicio);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $this->hora_fin);
        
        $diferencia = $fin->diff($inicio);
        return $diferencia->format('%h horas %i minutos');
    }

    /**
     * Verificar si el acta tiene compromisos pendientes
     */
    public function tieneCompromisosPendientes()
    {
        return $this->compromisos()->where('estado', 'Pendiente')->exists();
    }

    /**
     * Obtener el total de horas facturables del acta
     */
    public function getTotalHorasFacturablesAttribute()
    {
        return $this->resumenes()->where('facturable', true)->sum('horas');
    }

    /**
     * Obtener el total de horas del acta
     */
    public function getTotalHorasAttribute()
    {
        return $this->resumenes()->sum('horas');
    }

    /**
     * Verificar si el acta está completa (tiene todos los campos requeridos)
     */
    public function estaCompleta()
    {
        $camposRequeridos = [
            'tipo_id', 'numero', 'fecha', 'hora_inicio', 'hora_fin',
            'lugar', 'ciudad_id', 'empresa_id', 'proyecto_id',
            'objetivo', 'agenda', 'desarrollo', 'conclusiones',
            'firmante_empresa_id', 'firmante_gp_id'
        ];

        foreach ($camposRequeridos as $campo) {
            if (empty($this->$campo)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtener información resumida del acta
     */
    public function getResumenAttribute()
    {
        return [
            'numero' => $this->numero,
            'fecha' => $this->fecha_formateada,
            'tipo' => $this->tipoActa->nombre ?? 'Sin tipo',
            'proyecto' => $this->proyecto->nombre ?? 'Sin proyecto',
            'empresa' => $this->empresa->nombre ?? 'Sin empresa',
            'lugar' => $this->lugar,
            'asistentes' => $this->actaPersonas->count(),
            'compromisos' => $this->compromisos->count(),
            'compromisos_pendientes' => $this->compromisos()->where('estado', 'Pendiente')->count(),
            'horas_totales' => $this->total_horas,
            'horas_facturables' => $this->total_horas_facturables,
            'facturable' => $this->facturable ? 'Sí' : 'No',
            'version' => $this->info_version
        ];
    }

    // ✅ SCOPES ÚTILES
    
    /**
     * Scope para filtrar actas por proyecto
     */
    public function scopePorProyecto($query, $proyectoId)
    {
        return $query->where('proyecto_id', $proyectoId);
    }

    /**
     * Scope para filtrar actas por empresa
     */
    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Scope para filtrar actas por tipo
     */
    public function scopePorTipo($query, $tipoId)
    {
        return $query->where('tipo_id', $tipoId);
    }

    /**
     * Scope para filtrar actas por rango de fechas
     */
    public function scopePorRangoFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para obtener actas facturables
     */
    public function scopeFacturables($query)
    {
        return $query->where('facturable', true);
    }

    /**
     * Scope para obtener actas con compromisos pendientes
     */
    public function scopeConCompromisosPendientes($query)
    {
        return $query->whereHas('compromisos', function($q) {
            $q->where('estado', 'Pendiente');
        });
    }

    /**
     * Scope para cargar todas las relaciones necesarias
     */
    public function scopeConRelacionesCompletas($query)
    {
        return $query->with([
            'tipoActa',
            'ciudad.pais',
            'empresa',
            'proyecto',
            'firmanteEmpresa',
            'firmanteGp',
            'actaPersonas.persona',
            'actaPersonas.empresa',
            'compromisos.actaPersona.persona',
            'resumenes',
            'version'
        ]);
    }
} // ✅ CERRAR CLASE CORRECTAMENTE