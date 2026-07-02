<?php

use App\Legacy\LegacyApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Endpoint de compatibilidad con el API legacy
|--------------------------------------------------------------------------
| Los clientes existentes llaman a /api.php?w=<modulo>&r=<accion> (GET,
| POST form/multipart o JSON crudo). public/api.php garantiza que la URL
| exacta funcione aunque el servidor no reescriba a index.php.
*/

Route::any('/api.php', LegacyApiController::class);
