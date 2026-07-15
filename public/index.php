<?php

// Actualizador automático seguro: Visita arleysoftx.com/?pull=arleysoft para actualizar desde GitHub
if (isset($_GET['pull']) && $_GET['pull'] === 'arleysoft') {
    header('Content-Type: text/plain');
    echo "Iniciando actualización desde GitHub (git pull)...\n\n";
    $output = shell_exec('cd /home/arlenoug/repositories/arleysoftx.com && git pull 2>&1');
    echo $output;
    
    if (isset($_GET['debug_env'])) {
        $envPath = '/home/arlenoug/repositories/arleysoftx.com/.env';
        echo "\nChecking .env at: $envPath\n";
        echo "Exists: " . (file_exists($envPath) ? 'YES' : 'NO') . "\n";
        if (file_exists($envPath)) {
            $lines = file($envPath);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), 'DB_') || str_starts_with(trim($line), 'APP_')) {
                    echo trim($line) . "\n";
                }
            }
        }
        $envPath2 = '/home/arlenoug/public_html/.env';
        echo "\nChecking .env at: $envPath2\n";
        echo "Exists: " . (file_exists($envPath2) ? 'YES' : 'NO') . "\n";
    }

    echo "\nEjecutando migraciones de base de datos...\n";
    $migrateOutput = shell_exec('cd /home/arlenoug/repositories/arleysoftx.com && php artisan migrate --force 2>&1');
    echo $migrateOutput;

    echo "\nCopiando nuevos archivos públicos a public_html...\n";
    $copyOutput = shell_exec('cp -r /home/arlenoug/repositories/arleysoftx.com/public/* /home/arlenoug/public_html/ 2>&1');
    echo $copyOutput;
    
    if (isset($_GET['clean'])) {
        echo "\nLimpiando tareas cron del servidor...\n";
        $cronOutput = shell_exec('crontab -r 2>&1');
        echo $cronOutput;
    }
    
    echo "\n¡Actualización completada con éxito!";
    exit;
}

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../repositories/arleysoftx.com/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../repositories/arleysoftx.com/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../repositories/arleysoftx.com/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
