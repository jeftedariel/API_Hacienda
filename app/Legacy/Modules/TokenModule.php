<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Services\Hacienda\TokenService;

/**
 * Port de legacy/api/contrib/token (OAuth contra el IDP de Hacienda).
 */
class TokenModule implements LegacyModule
{
    public function __construct(private readonly TokenService $tokens) {}

    public function routes(): array
    {
        $action = fn (LegacyParams $p): mixed => $this->tokens->requestToken([
            'client_id' => (string) $p->get('client_id', ''),
            'grant_type' => (string) $p->get('grant_type', ''),
            'client_secret' => (string) $p->get('client_secret', ''),
            'username' => (string) $p->get('username', ''),
            'password' => (string) $p->get('password', ''),
            'refresh_token' => (string) $p->get('refresh_token', ''),
        ]);

        return [
            new LegacyRoute(
                r: 'gettoken',
                action: $action,
                params: [
                    ['key' => 'grant_type', 'def' => '', 'req' => true],
                    ['key' => 'client_id', 'def' => '', 'req' => true],
                    ['key' => 'client_secret', 'def' => '', 'req' => false],
                    ['key' => 'username', 'def' => '', 'req' => true],
                    ['key' => 'password', 'def' => '', 'req' => true],
                ],
            ),
            new LegacyRoute(
                r: 'refresh',
                action: $action,
                params: [
                    ['key' => 'grant_type', 'def' => '', 'req' => true],
                    ['key' => 'client_id', 'def' => '', 'req' => true],
                    ['key' => 'client_secret', 'def' => '', 'req' => false],
                    ['key' => 'refresh_token', 'def' => '', 'req' => true],
                ],
            ),
        ];
    }
}
