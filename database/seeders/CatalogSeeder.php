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

            // Normaliza al mismo conjunto de columnas (algunas filas del dump
            // legacy traen menos campos) y descarta el id del dump para dejar
            // que el autoincremento asigne uno limpio.
            $columns = array_values(array_filter(
                array_keys($rows[0] ?? []),
                fn ($c) => $c !== 'id'
            ));
            $rows = array_map(function (array $row) use ($columns): array {
                $normalized = [];
                foreach ($columns as $col) {
                    $normalized[$col] = $row[$col] ?? '';
                }

                return $normalized;
            }, $rows);

            // codificacion_mh: descarta filas sin provincia (artefactos de
            // parseo de valores con comas/comillas en el dump legacy).
            if ($table === 'codificacion_mh') {
                $rows = array_values(array_filter(
                    $rows,
                    fn ($r) => ($r['id_provincia'] ?? '') !== ''
                ));
            }

            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table($table)->insert($chunk);
            }

            $this->command?->info("$table: ".count($rows).' filas');
        }
    }
}
