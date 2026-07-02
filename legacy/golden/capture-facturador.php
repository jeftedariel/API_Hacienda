#!/usr/bin/env php
<?php
/**
 * Golden masters del módulo facturador. Requiere el stack legacy con las
 * "Tablas para Facturador CRLibre.sql" cargadas (catálogos + plantillas) y
 * el usuario master goldenuser ya registrado con su empresa.
 *
 * Uso: php capture-facturador.php [baseUrl] [outDir]
 */

require __DIR__.'/capture-lib.php';

$baseUrl = $argv[1] ?? 'http://localhost:8080/api.php';
$outDir  = $argv[2] ?? dirname(__DIR__, 2).'/tests/Fixtures/golden';

// Rutas de catálogo/geo (open access, deterministas).
$cases = [
    ['name' => 'fact-provinces', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'get_all_privinces']],
    ['name' => 'fact-cantons', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'get_cantons', 'idProvince' => '1']],
    ['name' => 'fact-districts', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'get_district', 'idProvince' => '1', 'idCanton' => '01']],
    ['name' => 'fact-neighborhoods', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'get_neighborhood', 'idProvince' => '1', 'idCanton' => '01', 'idDistrito' => '01']],
    ['name' => 'fact-type-of-id', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'get_type_of_id']],
    ['name' => 'fact-location-info', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'getCompannyLocationInformation',
                  'idProvincia' => '1', 'idCanton' => '01', 'idDistrito' => '01', 'idBarrio' => '01']],
    ['name' => 'fact-info', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'info']],
    ['name' => 'fact-cantons-missing-param', 'compare' => 'exact',
     'params' => ['w' => 'facturador', 'r' => 'get_cantons']],
];

$seq = 61;
foreach ($cases as $case) {
    $resp = callApi($baseUrl, $case);
    saveFixture($outDir, $seq++, $case, $resp);
}

echo "\nListo.\n";
