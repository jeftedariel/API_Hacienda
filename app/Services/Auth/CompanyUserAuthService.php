<?php

namespace App\Services\Auth;

use App\Models\CompanyUser;
use App\Models\CompanyUserSession;
use App\Models\LegacySession;
use Illuminate\Http\Request;

/**
 * Autenticación de sub-usuarios de empresa (ex companny_user.php del
 * facturador). El company_id equivale al idMasterUser legacy (== users.id
 * del dueño, que era el prefijo de las tablas dinámicas).
 *
 * El gate legacy es laxo (solo sessionKey) en facturadorCRLibre.php y
 * estricto (sessionKey+ip+idUser) en companny_user.php. Aquí se unifica en
 * el estricto, salvo que el contrato observado exija lo contrario.
 */
class CompanyUserAuthService
{
    /** Resuelve el idMasterUser (== company_id) desde el sessionKey del master. */
    public function masterUserIdFromSession(string $sessionKey): ?int
    {
        return LegacySession::where('session_key', $sessionKey)->value('user_id');
    }

    public function loadByUserNameOrEmail(int $companyId, string $identifier): ?CompanyUser
    {
        $q = CompanyUser::where('company_id', $companyId);

        return (str_contains($identifier, '@') && strpos($identifier, '@') > 0)
            ? $q->where('email', $identifier)->first()
            : $q->where('user_name', $identifier)->first();
    }

    public function verifyPassword(CompanyUser $user, string $plain): bool
    {
        if ($user->password !== null && password_verify($plain, $user->password)) {
            return true;
        }

        if ($user->legacy_md5 !== null && hash_equals($user->legacy_md5, md5($plain))) {
            $user->update(['password' => $this->hash($plain), 'legacy_md5' => null]);

            return true;
        }

        return false;
    }

    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    public function generateSessionKey(CompanyUser $user, Request $request): string
    {
        CompanyUserSession::where('company_user_id', $user->id)->delete();

        $sessionKey = password_hash((string) (time() * rand(0, 1000)), PASSWORD_DEFAULT);

        CompanyUserSession::create([
            'company_id' => $user->company_id,
            'company_user_id' => $user->id,
            'session_key' => $sessionKey,
            'ip' => (string) $request->ip(),
            'last_access' => time(),
        ]);

        return $sessionKey;
    }

    public function confirmSession(int $companyId, string $sessionKey, Request $request): ?CompanyUser
    {
        $session = CompanyUserSession::where('company_id', $companyId)
            ->where('session_key', $sessionKey)
            ->where('ip', (string) $request->ip())
            ->first();

        if ($session === null) {
            return null;
        }

        $lifetime = (int) config('hacienda.legacy.session_lifetime', 3600);
        if ($lifetime !== -1 && (time() - $session->last_access) > $lifetime) {
            return null;
        }

        return CompanyUser::find($session->company_user_id);
    }

    /** Gate companny_users_loggedIn: idMasterUser + iam + sessionKey + IP. */
    public function authenticate(?int $companyId, ?string $sessionKey, Request $request): ?CompanyUser
    {
        if (! $companyId || $sessionKey === null || $sessionKey === '') {
            return null;
        }

        return $this->confirmSession($companyId, $sessionKey, $request);
    }
}
