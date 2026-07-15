<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TikTokTutorialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page for arleysoftx.com
Route::get('/', function () {
    return view('home');
});

// Rutas para la venta del tutorial de POV con Stripe (Tutoriales TASK)
Route::get('/tutoriales-task', [TikTokTutorialController::class, 'showLanding'])->name('tutorial.landing');
Route::post('/tutoriales-task/checkout', [TikTokTutorialController::class, 'createCheckoutSession'])->name('tutorial.checkout');
Route::get('/tutoriales-task/success', [TikTokTutorialController::class, 'success'])->name('tutorial.success');
Route::get('/tutoriales-task/cancel', [TikTokTutorialController::class, 'cancel'])->name('tutorial.cancel');

// Ruta para el reporte de las últimas IA Generativas
Route::get('/reporte-ia', function () {
    return view('ia_report');
})->name('ia.report');

use App\Http\Controllers\TaskTutorialController;

// Rutas para la Guía y Tutoriales de las tareas TASK
Route::get('/guia-y-tutoriales-task', [TaskTutorialController::class, 'index'])->name('tutorial.task');
Route::get('/guia-y-tutoriales-task/admin', [TaskTutorialController::class, 'adminIndex'])->name('tutorial.task.admin');
Route::post('/guia-y-tutoriales-task/admin/save', [TaskTutorialController::class, 'save'])->name('tutorial.task.save');

Route::get('/guia-y-tutoriales-task/debug-images', function () {
    $dir = public_path('images');
    if (!file_exists($dir)) {
        return response()->json(['error' => 'Directory does not exist']);
    }
    $files = scandir($dir);
    $result = [];
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        $result[] = [
            'name' => $file,
            'size' => filesize($path),
            'perms' => substr(sprintf('%o', fileperms($path)), -4),
            'readable' => is_readable($path),
        ];
    }
    return response()->json($result);
});

