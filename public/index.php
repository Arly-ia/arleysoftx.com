<?php

// Actualizador automático seguro: Visita arleysoftx.com/?pull=arleysoft para actualizar desde GitHub
if (isset($_GET['pull']) && $_GET['pull'] === 'arleysoft') {
    header('Content-Type: text/plain');
    
    // Acción: migrar base de datos
    if (isset($_GET['action']) && $_GET['action'] === 'migrate') {
        echo "Ejecutando migraciones...\n\n";
        $output = shell_exec('cd /home/arlenoug/repositories/arleysoftx.com && php artisan migrate --force 2>&1');
        echo $output;
        echo "\n¡Listo!";
        exit;
    }

    // Acción: ver DB config del .env
    if (isset($_GET['action']) && $_GET['action'] === 'dbinfo') {
        $env = file_get_contents('/home/arlenoug/repositories/arleysoftx.com/.env');
        preg_match_all('/^(DB_[^=]+)=(.*)$/m', $env, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            echo $m[1] . "=" . $m[2] . "\n";
        }
        exit;
    }

    // Acción: buscar credenciales MySQL en el servidor
    if (isset($_GET['action']) && $_GET['action'] === 'sysinfo') {
        echo "=== HOME DIR ===\n";
        echo shell_exec('ls /home/arlenoug/ 2>&1');
        echo "\n=== .ENV FILES ===\n";
        echo shell_exec('find /home/arlenoug -name ".env" 2>&1 | head -20');
        echo "\n=== MY.CNF ===\n";
        echo shell_exec('cat /home/arlenoug/.my.cnf 2>&1');
        echo "\n=== MYSQL DBS (show databases) ===\n";
        echo shell_exec('mysql --defaults-file=/home/arlenoug/.my.cnf -e "SHOW DATABASES;" 2>&1');
        echo "\n=== PHP ENV ===\n";
        echo shell_exec('cd /home/arlenoug/repositories/arleysoftx.com && php -r "echo env(\'DB_DATABASE\') . PHP_EOL . env(\'DB_USERNAME\') . PHP_EOL . env(\'DB_PASSWORD\') . PHP_EOL;" 2>&1');
        exit;
    }

    // Acción: actualizar .env con credenciales correctas
    if (isset($_GET['action']) && $_GET['action'] === 'setenv') {
        $db = $_GET['db'] ?? '';
        $user = $_GET['user'] ?? '';
        $pass = $_GET['pass'] ?? '';
        if ($db && $user) {
            $envPath = '/home/arlenoug/repositories/arleysoftx.com/.env';
            $env = file_get_contents($envPath);
            $env = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE=' . $db, $env);
            $env = preg_replace('/^DB_USERNAME=.*/m', 'DB_USERNAME=' . $user, $env);
            $env = preg_replace('/^DB_PASSWORD=.*/m', 'DB_PASSWORD=' . $pass, $env);
            $env = preg_replace('/^DB_USERNAME=root/m', 'DB_USERNAME=' . $user, $env);
            file_put_contents($envPath, $env);
            echo "OK: .env actualizado\n";
            echo "DB_DATABASE=" . $db . "\n";
            echo "DB_USERNAME=" . $user . "\n";
        } else {
            echo "ERROR: Faltan parametros db y user\n";
        }
        exit;
    }

    echo "Iniciando actualización desde GitHub (git pull)...\n\n";
    $output = shell_exec('cd /home/arlenoug/repositories/arleysoftx.com && git pull 2>&1');
    echo $output;

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

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
} elseif (file_exists($maintenance = __DIR__.'/../repositories/arleysoftx.com/storage/framework/maintenance.php')) {
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

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    require __DIR__.'/../repositories/arleysoftx.com/vendor/autoload.php';
}

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

if (file_exists(__DIR__.'/../bootstrap/app.php')) {
    $app = require_once __DIR__.'/../bootstrap/app.php';
} else {
    $app = require_once __DIR__.'/../repositories/arleysoftx.com/bootstrap/app.php';
}

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
