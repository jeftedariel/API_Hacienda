<?php

/**
 * Punto de entrada de compatibilidad con el API legacy.
 *
 * Los clientes existentes llaman a https://host/api.php?w=<modulo>&r=<accion>.
 * Este shim despacha esas peticiones a través del kernel HTTP de Laravel,
 * donde routes/legacy.php las enruta hacia App\Legacy\LegacyApiController.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

// Al ejecutarse este archivo como script (Apache/nginx/servidor embebido),
// SCRIPT_NAME es /api.php y Symfony calcularía pathInfo = "/", con lo que la
// ruta legacy no matchearía. Presentarse como index.php hace que la petición
// conserve su URI real (/api.php) dentro del router de Laravel.
$_SERVER['SCRIPT_NAME'] = str_replace('api.php', 'index.php', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
if (isset($_SERVER['SCRIPT_FILENAME'])) {
    $_SERVER['SCRIPT_FILENAME'] = str_replace('api.php', 'index.php', $_SERVER['SCRIPT_FILENAME']);
}

(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
