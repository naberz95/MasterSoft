<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Version extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'fecha_creacion',
        'descripcion_cambio',
        'revisado_por',
        'fecha_revision',
        'aprobado_por',
        'fecha_aprobado',
        'estado',
        'fecha_aprobacion_documento'
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
        'fecha_revision' => 'date',
        'fecha_aprobado' => 'date',
        'fecha_aprobacion_documento' => 'date',
    ];

    /**
     * ✅ EVENTO PARA AUTO-INCREMENTAR VERSIÓN
     */
    protected static function boot()
    {
        parent::boot();

        // Evento que se ejecuta ANTES de crear un nuevo registro
        static::creating(function ($version) {
            // Solo auto-incrementar si no se especificó una versión
            if (empty($version->version) || $version->version == 0) {
                $ultimaVersion = static::max('version') ?? 0;
                $version->version = $ultimaVersion + 1;
                
                Log::info('🔢 Auto-incrementando versión', [
                    'ultima_version' => $ultimaVersion,
                    'nueva_version' => $version->version
                ]);
            } else {
                Log::info('📋 Usando versión especificada', [
                    'version_especificada' => $version->version
                ]);
            }
        });
    }

    // ✅ ELIMINAR COMPLETAMENTE EL MUTATOR setVersionAttribute
    // (Comentar o eliminar este método)
    /*
    public function setVersionAttribute($value)
    {
        // ESTE MÉTODO YA NO SE NECESITA
    }
    */

    /**
     * Formatear descripción del cambio (capitalizar primera letra)
     */
    public function setDescripcionCambioAttribute($value)
    {
        $this->attributes['descripcion_cambio'] = ucfirst(trim($value));
    }

    // ...resto del código permanece igual...
    
    public function actas()
    {
        return $this->hasMany(Acta::class);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'Aprobado');
    }

    public function scopeRevisadas($query)
    {
        return $query->where('estado', 'Revisado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    public function scopeOrdenadas($query, $direction = 'asc')
    {
        return $query->orderBy('version', $direction);
    }

    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_creacion', [$fechaInicio, $fechaFin]);
    }

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'Pendiente' => '<span class="badge bg-warning text-dark">Pendiente</span>',
            'Revisado' => '<span class="badge bg-info">Revisado</span>',
            'Aprobado' => '<span class="badge bg-success">Aprobado</span>',
        ];

        return $badges[$this->estado] ?? '<span class="badge bg-secondary">Sin estado</span>';
    }

    public function getVersionFormateadaAttribute()
    {
        return "Versión {$this->version}";
    }

    public function getDescripcionCortaAttribute()
    {
        return strlen($this->descripcion_cambio) > 50 
            ? substr($this->descripcion_cambio, 0, 50) . '...' 
            : $this->descripcion_cambio;
    }

    public function estaCompletamenteAprobada()
    {
        return $this->estado === 'Aprobado' 
            && !is_null($this->aprobado_por) 
            && !is_null($this->fecha_aprobado);
    }

    public function estaEnProceso()
    {
        return in_array($this->estado, ['Pendiente', 'Revisado']);
    }

    public function diasTranscurridos()
    {
        return $this->fecha_creacion->diffInDays(Carbon::now());
    }

    public static function siguienteNumero()
    {
        $ultimaVersion = static::max('version');
        return ($ultimaVersion ?? 0) + 1;
    }

    public static function masReciente()
    {
        return static::ordenadas('desc')->first();
    }

    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_REVISADO = 'Revisado';
    const ESTADO_APROBADO = 'Aprobado';

    public static function getEstados()
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_REVISADO => 'Revisado',
            self::ESTADO_APROBADO => 'Aprobado',
        ];
    }

    public function contarActas()
    {
        return $this->actas()->count();
    }

    public function puedeSerEliminada()
    {
        return $this->contarActas() === 0;
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('descripcion_cambio', 'LIKE', "%{$termino}%")
              ->orWhere('revisado_por', 'LIKE', "%{$termino}%")
              ->orWhere('aprobado_por', 'LIKE', "%{$termino}%")
              ->orWhere('version', 'LIKE', "%{$termino}%");
        });
    }

    public function __toString()
    {
        return "Versión {$this->version} - {$this->descripcion_corta}";
    }
}