<?php

namespace App\Providers;

use App\Legacy\LegacyDispatcher;
use App\Legacy\Modules\CalaModule;
use App\Legacy\Modules\EjemploModule;
use App\Legacy\Modules\VersionModule;
use Illuminate\Support\ServiceProvider;

class LegacyServiceProvider extends ServiceProvider
{
    /**
     * Registro de módulos legacy: parámetro w => clase del módulo.
     * Se van agregando conforme avanza el port (ver plan de migración).
     *
     * @var array<string, class-string<\App\Legacy\LegacyModule>>
     */
    public const MODULES = [
        'cala' => CalaModule::class,
        'version' => VersionModule::class,
        'ejemplo' => EjemploModule::class,
    ];

    public function register(): void
    {
        $this->app->singleton(LegacyDispatcher::class, function ($app) {
            return new LegacyDispatcher($app, self::MODULES);
        });
    }
}
