<?php

namespace App\Legacy;

/**
 * Equivalente a un módulo legacy (api/modules/<w> o api/contrib/<w>):
 * expone su tabla de rutas, como hacía <modulo>_init().
 */
interface LegacyModule
{
    /** @return array<int, LegacyRoute> */
    public function routes(): array;
}
