<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Files\FileStorageService;

/**
 * Port de legacy/api/contrib/XmlToBase64: devuelve el contenido de un
 * archivo subido (por downloadCode) en base64. Con código inexistente el
 * legacy respondía "" (file_get_contents suprimido sobre path inválido).
 */
class XmlToBase64Module implements LegacyModule
{
    public function __construct(private readonly FileStorageService $files) {}

    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'encode',
                action: function (LegacyParams $p): string {
                    $path = $this->files->findPathByDownloadCode((string) $p->get('downloadCode'));

                    return base64_encode($path !== null ? (string) @file_get_contents($path) : '');
                },
                params: [
                    ['key' => 'downloadCode', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
