<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Se debe decidir ANTES de que Scramble registre sus rutas (en su
        // propio boot). Con SCRAMBLE_ENABLED=false (recomendado en prod) no
        // se exponen /docs/api ni /docs/api.json.
        if (! config('scramble.enabled', true)) {
            Scramble::ignoreDefaultRoutes();
        }
    }

    public function boot(): void
    {
        if (config('scramble.enabled', true)) {
            // Declara el esquema de seguridad Bearer para todos los endpoints.
            Scramble::configure()->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(SecurityScheme::http('bearer'));
            });
        }
    }
}
