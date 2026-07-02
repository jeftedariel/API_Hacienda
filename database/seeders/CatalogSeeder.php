<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catálogos del Ministerio de Hacienda (geografía CR, tipos de cédula,
 * impuestos, medios de pago, situaciones, unidades de medida). Los datos
 * fueron extraídos de legacy/recursos/"Tablas para Facturador CRLibre.sql"
 * a database/seeders/data/*.json.
 */
class CatalogSeeder extends Seeder
{
    private const TABLES = [
        'codificacion_mh',
        'tipo_cedula',
        'tipo_impuestos',
        'medio_pago',
        'tipo_situacion',
        'unidad_medida',
    ];

    public function run(): void
    {
        foreach (self::TABLES as $table) {
            if (DB::table($table)->exists()) {
                continue; // idempotente
            }

            $rows = json_decode(
                file_get_contents(database_path("seeders/data/$table.json")),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table($table)->insert($chunk);
            }

            $this->command?->info("$table: ".count($rows).' filas');
        }
    }
}
