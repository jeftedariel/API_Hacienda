<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;

/**
 * Módulo por defecto del legacy (w ausente => 'cala'). Sus rutas reales
 * (cala_core, cala_default, cala_test_install) eran utilitarias de
 * instalación y no forman parte del contrato de los clientes; cualquier r
 * responde "Function not found", igual que el legacy para rutas no
 * registradas.
 */
class CalaModule implements LegacyModule
{
    public function routes(): array
    {
        return [];
    }
}
