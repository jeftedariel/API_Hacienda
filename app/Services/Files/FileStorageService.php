<?php

namespace App\Services\Files;

/**
 * Resolución de archivos subidos por downloadCode (equivalente a
 * filesGetUrl() de legacy/api/modules/files/module.php).
 *
 * En la Fase 4 esta clase quedará respaldada por la tabla stored_files y
 * storage/app; por ahora resuelve contra un directorio local opcional para
 * poder ejercitar la firma en tests.
 */
class FileStorageService
{
    /**
     * Devuelve la ruta local del archivo o null si el código no existe.
     * El legacy devolvía un path inválido y el caller hacía
     * file_get_contents() suprimido => string vacío.
     */
    public function findPathByDownloadCode(string $downloadCode): ?string
    {
        if ($downloadCode === '') {
            return null;
        }

        // TODO(Fase 4): consultar stored_files (ex tabla files) en BD.
        $dir = storage_path('app/legacy-files');
        $candidate = $dir.'/'.$downloadCode;

        return is_file($candidate) ? $candidate : null;
    }
}
