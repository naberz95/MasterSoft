<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\CiudadController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TipoActaController; 
use App\Http\Controllers\VersionController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    
    // DASHBOARD - Página principal después del login
    Route::get('/home', function () {
        return redirect('/dashboard');
    })->name('home');

    Route::get('/dashboard', function () {
        // Estadísticas rápidas
        $stats = [
            'total_actas' => \App\Models\Acta::count(),
            'total_empresas' => \App\Models\Empresa::count(),
            'total_personas' => \App\Models\Persona::count(),
            'total_proyectos' => \App\Models\Proyecto::count(),
            'total_ciudades' => \App\Models\Ciudad::count(),
            'total_paises' => \App\Models\Pais::count(),
            'actas_mes' => \App\Models\Acta::whereMonth('created_at', now()->month)->count(),
        ];

        // Últimas actas para mostrar en dashboard
        $ultimasActas = \App\Models\Acta::with(['empresa', 'proyecto', 'ciudad'])
                                       ->latest()
                                       ->take(5)
                                       ->get();

        return view('dashboard', compact('stats', 'ultimasActas'));
    })->name('dashboard');
    
    // ACTAS - Ruta principal del sistema
    Route::resource('actas', ActaController::class);
    Route::get('actas/{acta}/pdf', [ActaController::class, 'generarPDF'])->name('actas.pdf');

    Route::get('/actas/{proyectoId}/ultima', [ActaController::class, 'ultimaActaPorProyecto'])->name('actas.ultima');
    Route::get('/proyectos/{proyectoId}/resumen-cronologico', [ActaController::class, 'resumenCronologicoPorProyecto'])->name('proyectos.resumen-cronologico');
    Route::get('/actas/{acta}/export-pdf', [ActaController::class, 'exportPdf'])->name('actas.exportPdf');
    
    // APIs para gestión de personas en actas
    Route::post('actas/{acta}/personas', [ActaController::class, 'agregarPersona'])->name('actas.agregar_persona');
    Route::delete('actas/{acta}/personas/{actaPersona}', [ActaController::class, 'eliminarPersona'])->name('actas.eliminar_persona');
    Route::put('actas/{acta}/personas/{actaPersona}', [ActaController::class, 'actualizarPersona'])->name('actas.actualizar_persona');
    
    // APIs para gestión de compromisos
    Route::post('actas/{acta}/compromisos', [ActaController::class, 'agregarCompromiso'])->name('actas.agregar_compromiso');
    Route::put('compromisos/{compromiso}', [ActaController::class, 'actualizarCompromiso'])->name('compromisos.actualizar');
    Route::delete('compromisos/{compromiso}', [ActaController::class, 'eliminarCompromiso'])->name('compromisos.eliminar');

    // EMPRESAS
    Route::resource('empresas', EmpresaController::class);

    // PERSONAS  
    Route::resource('personas', PersonaController::class);

    // PROYECTOS
    Route::resource('proyectos', ProyectoController::class);

    // PAÍSES
    Route::resource('paises', PaisController::class)->parameters([
        'paises' => 'pais'
    ]);

    // CIUDADES
    Route::resource('ciudades', CiudadController::class)->parameters([
        'ciudades' => 'ciudad'
    ]);

    // TIPOS DE ACTA
    Route::resource('tipos-acta', TipoActaController::class);

    // 📝 CKEDITOR UPLOAD - ¡NUEVA RUTA NECESARIA!
    Route::post('/ckeditor/upload', function (Illuminate\Http\Request $request) {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');
            
            $url = asset('storage/' . $path);
            
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }
        
        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'No se pudo subir el archivo'
            ]
        ]);
    })->name('ckeditor.upload');


// 📋 RUTAS PARA VERSIONES (solo las que necesitamos)
Route::get('/versions', [VersionController::class, 'index'])->name('versions.index');
Route::get('/versions/create', [VersionController::class, 'create'])->name('versions.create');
Route::post('/versions', [VersionController::class, 'store'])->name('versions.store');
Route::get('/versions/{version}/edit', [VersionController::class, 'edit'])->name('versions.edit');
Route::put('/versions/{version}', [VersionController::class, 'update'])->name('versions.update');

// 🔢 RUTAS ADICIONALES PARA VERSIONES (APIs)
Route::get('/versions/api/next-number', [VersionController::class, 'getNextNumber'])->name('versions.next-number');
Route::get('/versions/api/estadisticas', [VersionController::class, 'estadisticas'])->name('versions.estadisticas');
Route::get('/versions/api/buscar', [VersionController::class, 'buscar'])->name('versions.buscar');
Route::get('/versions/api/para-select', [VersionController::class, 'paraSelect'])->name('versions.para-select');

});