<?php

namespace App\Providers;

use App\Legacy\LegacyDispatcher;
use App\Legacy\Modules\CalaModule;
use App\Legacy\Modules\CallbackModule;
use App\Legacy\Modules\ClaveModule;
use App\Legacy\Modules\ConsultarModule;
use App\Legacy\Modules\EjemploModule;
use App\Legacy\Modules\FirmarXmlModule;
use App\Legacy\Modules\CheckModule;
use App\Legacy\Modules\GenXmlModule;
use App\Legacy\Modules\MakeJsonModule;
use App\Legacy\Modules\MakeQrModule;
use App\Legacy\Modules\SendModule;
use App\Legacy\Modules\TokenModule;
use App\Legacy\Modules\XmlToBase64Module;
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
        'clave' => ClaveModule::class,
        'genXML' => GenXmlModule::class,
        'makeJson' => MakeJsonModule::class,
        'XmlToBase64' => XmlToBase64Module::class,
        'makeQR' => MakeQrModule::class,
        'check' => CheckModule::class,
        'firmarXML' => FirmarXmlModule::class,
        'token' => TokenModule::class,
        'send' => SendModule::class,
        'consultar' => ConsultarModule::class,
        'callback' => CallbackModule::class,
    ];

    public function register(): void
    {
        $this->app->singleton(LegacyDispatcher::class, function ($app) {
            return new LegacyDispatcher($app, self::MODULES);
        });
    }
}
