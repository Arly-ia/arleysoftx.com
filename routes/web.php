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
})->name('home');

// JJ Construccion page
Route::get('/jj-construccion', function () {
    return view('jj_construccion');
})->name('jj.construccion');

// JJ Construccion — 20 Wings Task Manager
use App\Http\Controllers\JJTaskController;
Route::get('/jj-construccion/20wings-tareas',                       [JJTaskController::class, 'index'])->name('jj.20wings.tasks');
Route::post('/jj-construccion/20wings-tareas/task',                 [JJTaskController::class, 'store'])->name('jj.20wings.store');
Route::patch('/jj-construccion/20wings-tareas/task/{id}/status',    [JJTaskController::class, 'updateStatus'])->name('jj.20wings.updateStatus');
Route::post('/jj-construccion/20wings-tareas/task/{id}/photo',      [JJTaskController::class, 'uploadPhoto'])->name('jj.20wings.uploadPhoto');
Route::delete('/jj-construccion/20wings-tareas/photo/{id}',         [JJTaskController::class, 'deletePhoto'])->name('jj.20wings.deletePhoto');
Route::post('/jj-construccion/20wings-tareas/task/{id}/receipt',    [JJTaskController::class, 'uploadReceipt'])->name('jj.20wings.uploadReceipt');
Route::delete('/jj-construccion/20wings-tareas/receipt/{id}',       [JJTaskController::class, 'deleteReceipt'])->name('jj.20wings.deleteReceipt');
Route::delete('/jj-construccion/20wings-tareas/task/{id}',          [JJTaskController::class, 'destroy'])->name('jj.20wings.destroy');


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
Route::post('/guia-y-tutoriales-task/admin/login', [TaskTutorialController::class, 'adminLogin'])->name('tutorial.task.admin.login');
Route::get('/guia-y-tutoriales-task/admin/logout', [TaskTutorialController::class, 'adminLogout'])->name('tutorial.task.admin.logout');
Route::post('/guia-y-tutoriales-task/admin/save', [TaskTutorialController::class, 'save'])->name('tutorial.task.save');

// Rutas de acceso con clave de licencia
use App\Http\Controllers\TaskAccessController;
Route::get('/guia-y-tutoriales-task/acceso',  [TaskAccessController::class, 'showAccessPage'])->name('task.access');
Route::post('/guia-y-tutoriales-task/acceso', [TaskAccessController::class, 'verifyKey'])->name('task.access.verify');

// Rutas de administración de licencias (protegidas por sesión de admin)
Route::post('/guia-y-tutoriales-task/admin/license/{id}/revoke', [TaskAccessController::class, 'revokeLicense'])->name('task.license.revoke');
Route::post('/guia-y-tutoriales-task/admin/license/{id}/reset-devices', [TaskAccessController::class, 'resetDevices'])->name('task.license.reset');


// Ruta de migración web protegida (para ejecutar en producción desde el browser)
Route::get('/arleysoft-run-migrations-secure-7x2k', function () {
    $adminToken = request()->query('token');
    if ($adminToken !== env('TASK_ADMIN_PASSWORD')) {
        abort(403, 'Unauthorized');
    }
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response('<pre style="background:#000;color:#0f0;padding:20px;font-family:monospace">' . htmlspecialchars($output) . '</pre>');
    } catch (\Exception $e) {
        return response('<pre style="background:#000;color:#f00;padding:20px;font-family:monospace">ERROR: ' . htmlspecialchars($e->getMessage()) . '</pre>');
    }
});


// Ruta para resetear sesión de prueba (solo accesible conociendo el path)
Route::get('/guia-y-tutoriales-task/reset-session-arleysoft', function () {
    session()->forget('task_tutorial_paid');
    session()->forget('task_tutorial_session_id');
    return redirect()->route('tutorial.task')->with('success', 'Sesión de prueba limpiada. Ahora verás el modo preview.');
});

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

