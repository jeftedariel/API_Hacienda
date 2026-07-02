<?php

namespace App\Services\Files;

use App\Models\StoredFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;

/**
 * Almacenamiento de archivos subidos (ex módulo legacy `files`): réplica de
 * files_upload/filesGetUrl/files_createDownloadCode sobre la tabla
 * stored_files y storage/app.
 *
 * Códigos de error del legacy (strings; tools_reply no los mapea a HTTP):
 *   -400 demasiado grande, -402 error al subir, -404 extensión no permitida.
 */
class FileStorageService
{
    public const ERROR_TOO_BIG = '-400';

    public const ERROR_UPLOAD = '-402';

    public const ERROR_EXT_NOT_ALLOWED = '-404';

    public const DEFAULT_ALLOWED_EXT = 'jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF,p12,P12,pfx,PFX,xml,XML,Xml';

    public const DEFAULT_MAX_SIZE_MB = 2;

    /** Ruta local del archivo por downloadCode, o null si no existe. */
    public function findPathByDownloadCode(string $downloadCode): ?string
    {
        if ($downloadCode === '') {
            return null;
        }

        $file = StoredFile::where('download_code', $downloadCode)->first();
        if ($file === null) {
            return null;
        }

        $path = storage_path('app/'.$file->path);

        return is_file($path) ? $path : null;
    }

    public function findByDownloadCode(string $downloadCode): ?StoredFile
    {
        return $downloadCode === '' ? null : StoredFile::where('download_code', $downloadCode)->first();
    }

    /**
     * Réplica de files_upload(): valida extensión y tamaño, guarda en
     * storage/app/legacy-files/<user>/<type>/ y registra en stored_files.
     *
     * @return array{idFile: string, name: string, downloadCode: string}|string
     *                                                                          El shape legacy de éxito, o el código de error como string.
     */
    public function upload(
        User $user,
        ?UploadedFile $file,
        string $type = 'attach',
        string|false $finalName = false,
        string|false $ext = false,
        int $maxSizeMb = 0,
    ): array|string {
        if ($file === null || ! $file->isValid()) {
            return self::ERROR_UPLOAD;
        }

        $allowed = $ext === false ? self::DEFAULT_ALLOWED_EXT : $ext;
        $maxSizeMb = $maxSizeMb > 0 ? $maxSizeMb : self::DEFAULT_MAX_SIZE_MB;

        $originalName = $file->getClientOriginalName();
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = $finalName === false ? basename($originalName) : $finalName.'.'.$extension;

        if ($file->getSize() > $maxSizeMb * 1000000) {
            return self::ERROR_TOO_BIG;
        }

        if ($allowed !== '*' && ! in_array($extension, explode(',', $allowed), true)) {
            return self::ERROR_EXT_NOT_ALLOWED;
        }

        $relativeDir = 'legacy-files/'.$user->id.'/'.$type;
        $downloadCode = $this->createDownloadCode($name, $user->id);

        try {
            $file->storeAs($relativeDir, $name, ['disk' => 'local']);
        } catch (\Throwable) {
            return self::ERROR_UPLOAD;
        }

        $stored = StoredFile::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type, 'name' => $name],
            [
                'md5' => md5_file(storage_path('app/'.$relativeDir.'/'.$name)) ?: '',
                'legacy_timestamp' => time(),
                'size' => (int) $file->getSize(),
                'download_code' => $downloadCode,
                'file_type' => '',
                'path' => $relativeDir.'/'.$name,
            ]
        );

        // Contrato legacy: valores de BD como strings.
        return [
            'idFile' => (string) $stored->id,
            'name' => $name,
            'downloadCode' => $downloadCode,
        ];
    }

    /** files_createDownloadCode(): md5(nombre . "//" . time() . idUser). */
    public function createDownloadCode(string $name, int $userId): string
    {
        return md5($name.'//'.time().$userId);
    }
}
