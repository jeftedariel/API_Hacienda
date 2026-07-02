<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;

/**
 * Port de legacy/api/contrib/version (version_API()).
 */
class VersionModule implements LegacyModule
{
    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'version',
                action: fn (LegacyParams $p): string => $this->versionApi(),
            ),
        ];
    }

    private function versionApi(): string
    {
        $commit = trim((string) @exec('git describe --long --match init --abbrev=7'));

        if (strlen($commit) === 17) {
            $commit = preg_replace('/init\-([0-9]+)\-g/', '', $commit);
            if (strlen($commit) === 7) {
                return "Version: {$commit}";
            }
        } elseif (is_file($versionFile = config('hacienda.legacy.version_file'))) {
            $commit = trim((string) file_get_contents($versionFile));
            if (strlen($commit) === 7) {
                return "Version: {$commit}";
            }
        }

        return 'No tiene soporte git.';
    }
}
