<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Auth\LegacyAuthService;
use App\Services\Files\FileStorageService;

/**
 * Port de legacy/api/contrib/fileUploader: subida de certificados .p12 y
 * XML (requiere sesión de usuario master). El campo del formulario es
 * `fileToUpload` y la respuesta es {idFile, name, downloadCode} (strings).
 */
class FileUploaderModule implements LegacyModule
{
    public function __construct(
        private readonly FileStorageService $files,
        private readonly LegacyAuthService $auth,
    ) {
    }

    public function routes(): array
    {
        $upload = function (LegacyParams $p, string $ext) {
            $user = $this->auth->authenticate(
                (string) $p->get('iam', ''),
                (string) $p->get('sessionKey', ''),
                request()
            );
            if ($user === null) {
                return FileStorageService::ERROR_UPLOAD;
            }

            return $this->files->upload($user, request()->file('fileToUpload'), 'hacienda', false, $ext);
        };

        return [
            new LegacyRoute(
                r: 'subir_certif',
                access: LegacyRoute::ACCESS_USER,
                action: fn (LegacyParams $p) => $upload($p, 'p12'),
            ),
            new LegacyRoute(
                r: 'subir_xml',
                access: LegacyRoute::ACCESS_USER,
                action: fn (LegacyParams $p) => $upload($p, 'xml'),
            ),
            new LegacyRoute(
                r: 'test',
                action: fn (): string => 'Test :)',
            ),
        ];
    }
}
