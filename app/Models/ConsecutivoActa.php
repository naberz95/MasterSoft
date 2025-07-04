<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsecutivoActa extends Model
{
    protected $table = 'consecutivos_acta';
    
    protected $fillable = [
        'anio',
        'mes',
        'consecutivo'
    ];
    
    protected $casts = [
        'anio' => 'string',
        'mes' => 'string',
        'consecutivo' => 'integer'
    ];

    // Método para obtener o crear consecutivo
    public static function obtenerSiguienteConsecutivo($anio, $mes)
    {
        $consecutivo = self::firstOrCreate(
            ['anio' => $anio, 'mes' => $mes],
            ['consecutivo' => 0]
        );
        
        $consecutivo->increment('consecutivo');
        return $consecutivo->consecutivo;
    }

    // Método para generar número de acta
    public static function generarNumeroActa($fecha)
    {
        $anio = substr($fecha->format('Y'), -2); // Últimos 2 dígitos del año
        $mes = $fecha->format('m'); // Mes con 2 dígitos
        
        $consecutivo = self::obtenerSiguienteConsecutivo($anio, $mes);
        
        return sprintf('%s%s%03d', $anio, $mes, $consecutivo);
    }
}