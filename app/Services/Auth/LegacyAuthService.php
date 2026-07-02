<?php

namespace App\Services\Auth;

use App\Models\LegacySession;
use App\Models\User;
use App\Services\LegacyCrypto;
use Illuminate\Http\Request;

/**
 * Autenticación de usuarios de plataforma con la semántica del módulo
 * legacy `users`: identidad por parámetro `iam` (userName), sesión por
 * `sessionKey` + IP en la tabla legacy_sessions, una sesión por usuario.
 *
 * Diferencias deliberadas respecto al legacy:
 *  - Los passwords se guardan como bcrypt puro (el legacy los envolvía en
 *    AES+base64); el fallback md5 se conserva vía columna legacy_md5 y se
 *    rehashea en el primer login exitoso.
 */
class LegacyAuthService
{
    public function loadByUserNameOrEmail(string $identifier): ?User
    {
        if (str_contains($identifier, '@') && strpos($identifier, '@') > 0) {
            return User::where('email', $identifier)->first();
        }

        return User::where('user_name', $identifier)->first();
    }

    public function verifyPassword(User $user, string $plain): bool
    {
        if ($user->password !== null && password_verify($plain, $user->password)) {
            return true;
        }

        // Fallback md5 del legacy; si acierta, rehash transparente a bcrypt.
        if ($user->legacy_md5 !== null && hash_equals($user->legacy_md5, md5($plain))) {
            $user->update(['password' => $this->hash($plain), 'legacy_md5' => null]);

            return true;
        }

        return false;
    }

    /** bcrypt cost 12, como users_hash() (sin la capa AES). */
    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Réplica de users_generateSessionKey(): sesión única por usuario y
     * sessionKey en el formato legacy (AES(bcrypt(aleatorio))) para que los
     * clientes que persisten llaves no noten diferencia de formato.
     */
    public function generateSessionKey(User $user, Request $request): string
    {
        LegacySession::where('user_id', $user->id)->delete();

        $sessionKey = LegacyCrypto::fromConfig()->encrypt(
            password_hash((string) (time() * rand(0, 1000)), PASSWORD_DEFAULT)
        );

        LegacySession::create([
            'user_id' => $user->id,
            'session_key' => $sessionKey,
            'ip' => (string) $request->ip(),
            'last_access' => time(),
        ]);

        return $sessionKey;
    }

    /**
     * Réplica de users_confirmSessionKey(): sessionKey + IP + idUser deben
     * coincidir; expiración según legacy.session_lifetime. Se conserva
     * incluso el comportamiento con lifetime -1 (el legacy no retornaba
     * nada => sesión inválida).
     */
    public function confirmSession(User $user, string $sessionKey, Request $request): bool
    {
        $session = LegacySession::where('session_key', $sessionKey)
            ->where('ip', (string) $request->ip())
            ->where('user_id', $user->id)
            ->first();

        if ($session === null) {
            return false;
        }

        $lifetime = (int) config('hacienda.legacy.session_lifetime', 3600);
        if ($lifetime !== -1) {
            return (time() - $session->last_access) <= $lifetime;
        }

        return false; // bug legacy replicado: lifetime -1 nunca valida
    }

    /**
     * Gate users_loggedIn: resuelve el usuario actual por `iam` y valida la
     * sesión. Devuelve el usuario autenticado o null.
     */
    public function authenticate(?string $iam, ?string $sessionKey, Request $request): ?User
    {
        if ($iam === null || $iam === '' || $sessionKey === null || $sessionKey === '') {
            return null;
        }

        $user = $this->loadByUserNameOrEmail($iam);
        if ($user === null) {
            return null;
        }

        return $this->confirmSession($user, $sessionKey, $request) ? $user : null;
    }

    /** Efecto colateral de _tools_reply(): refrescar lastAccess. */
    public function touchLastAccess(?string $sessionKey, ?User $user): void
    {
        if ($sessionKey === null || $sessionKey === '' || $user === null) {
            return;
        }

        LegacySession::where('session_key', $sessionKey)->update(['last_access' => time()]);
        $user->update(['last_access' => time()]);
    }

    public function destroySession(string $sessionKey, Request $request): void
    {
        LegacySession::where('session_key', $sessionKey)
            ->where('ip', (string) $request->ip())
            ->delete();
    }
}
