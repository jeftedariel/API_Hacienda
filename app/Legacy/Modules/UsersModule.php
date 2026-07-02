<?php

namespace App\Legacy\Modules;

use App\Legacy\LegacyModule;
use App\Legacy\LegacyParams;
use App\Legacy\LegacyRoute;
use App\Models\User;
use App\Services\Auth\LegacyAuthService;
use Illuminate\Support\Facades\Mail;

/**
 * Port de legacy/api/modules/users. Contratos conservados (ver golden
 * masters 38-45 y 51): todos los valores que venían de BD viajan como
 * strings (mysqli); el registro hace auto-login; usuario duplicado responde
 * {code:"-304", status:"usuario ya existe"} con status ok.
 *
 * Divergencia deliberada: users_get_my_details ya no expone el hash de la
 * contraseña (campo pwd presente pero vacío, shape intacto).
 */
class UsersModule implements LegacyModule
{
    public function __construct(private readonly LegacyAuthService $auth)
    {
    }

    public function routes(): array
    {
        return [
            new LegacyRoute(
                r: 'users_register',
                action: fn (LegacyParams $p) => $this->register($p),
                params: [
                    ['key' => 'fullName', 'def' => '', 'req' => true],
                    ['key' => 'userName', 'def' => '', 'req' => true],
                    ['key' => 'email', 'def' => '', 'req' => true],
                    ['key' => 'about', 'def' => '', 'req' => true],
                    ['key' => 'country', 'def' => '', 'req' => true],
                    ['key' => 'pwd', 'def' => '', 'req' => true],
                ],
            ),
            new LegacyRoute(
                r: 'users_log_me_in',
                action: fn (LegacyParams $p) => $this->login($p),
                params: [['key' => 'pwd', 'def' => '', 'req' => true]],
            ),
            new LegacyRoute(
                r: 'users_log_me_out',
                access: LegacyRoute::ACCESS_USER,
                action: function (LegacyParams $p): string {
                    $this->auth->destroySession((string) $p->get('sessionKey', ''), request());
                    $p->set('sessionKey', 'longGone');

                    return 'good bye';
                },
            ),
            new LegacyRoute(
                r: 'users_get_my_details',
                access: LegacyRoute::ACCESS_USER,
                action: function (LegacyParams $p): array|int {
                    $user = $this->auth->loadByUserNameOrEmail((string) $p->get('iam', ''));

                    return $user === null ? -1 : $this->legacyShape($user);
                },
            ),
            new LegacyRoute(
                r: 'users_recover_pwd',
                action: fn (LegacyParams $p) => $this->recoverPassword($p),
                params: [['key' => 'userName', 'def' => '', 'req' => true]],
            ),
            new LegacyRoute(
                r: 'users_update_profile',
                access: LegacyRoute::ACCESS_USER,
                action: fn (LegacyParams $p) => $this->updateProfile($p),
            ),
            new LegacyRoute(
                r: 'users_confirm_session_vilidity', // typo histórico: es contrato
                access: LegacyRoute::ACCESS_USER,
                action: fn (): int => 1, // SUCCESS_ALL_GOOD
            ),
            new LegacyRoute(
                r: 'users_get_list',
                access: LegacyRoute::ACCESS_NONE, // el legacy la tenía deshabilitada
                action: fn (): int => -1,
                params: [['key' => 'like', 'def' => '', 'req' => false]],
            ),
            new LegacyRoute(
                r: 'login_auto',
                // La función legacy users_autoLogin no existía: la ruta
                // respondía ERROR_BAD_REQUEST (-1) con status ok.
                action: fn (): int => -1,
                params: [
                    ['key' => 'idUser', 'def' => '0', 'req' => true],
                    ['key' => 'token', 'def' => 'ThisIsNotASecretToken', 'req' => true],
                ],
            ),
        ];
    }

    private function register(LegacyParams $p): array|string
    {
        $userName = trim((string) $p->get('userName'));
        $email = (string) $p->get('email');

        $existing = User::where('user_name', $userName)->orWhere('email', $email)->exists();
        if ($existing) {
            return ['code' => '-304', 'status' => 'usuario ya existe'];
        }

        User::create([
            'full_name' => (string) $p->get('fullName'),
            'user_name' => $userName,
            'email' => $email,
            'about' => (string) $p->get('about', 'May all beings be at ease'),
            'country' => (string) $p->get('country', 'crc'),
            'status' => '1',
            'legacy_timestamp' => time(),
            'last_access' => time(),
            'password' => $this->auth->hash((string) $p->get('pwd')),
            'avatar' => '0',
            'settings' => 'NULL',
        ]);

        // El registro legacy hace auto-login y devuelve el shape de login.
        $p->set('userName', $userName);

        return $this->login($p);
    }

    private function login(LegacyParams $p): array|string
    {
        $user = $this->auth->loadByUserNameOrEmail((string) $p->get('userName', ''));

        if ($user === null || ! $this->auth->verifyPassword($user, (string) $p->get('pwd', ''))) {
            return '-301'; // ERROR_USERS_WRONG_LOGIN_INFO -> 401
        }

        return [
            'sessionKey' => $this->auth->generateSessionKey($user, request()),
            'userName' => $user->user_name,
            'idUser' => (string) $user->id,
        ];
    }

    private function recoverPassword(LegacyParams $p): int
    {
        $user = $this->auth->loadByUserNameOrEmail((string) $p->get('userName', ''));
        if ($user === null) {
            return -1; // ERROR_BAD_REQUEST
        }

        $temp = (string) (rand(0, 1000) + time());
        $user->update(['password' => $this->auth->hash($temp), 'legacy_md5' => null]);

        try {
            Mail::html(
                '<p>Su nueva clave es: '.e($temp).'</p>',
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Recuperación de Clave '.config('app.name'))
                        ->replyTo((string) config('mail.reply_to.address', 'no-reply@crlibre.org'));
                }
            );
        } catch (\Throwable) {
            return -2; // ERROR_ERROR
        }

        return 1; // SUCCESS_ALL_GOOD
    }

    private function updateProfile(LegacyParams $p): array
    {
        $user = $this->auth->loadByUserNameOrEmail((string) $p->get('iam', ''));
        if ($user === null) {
            return ['code' => -2, 'status' => 'error registrando'];
        }

        $newUserName = $p->get('userName', false);
        if ($newUserName !== false && $newUserName !== $user->user_name
            && User::where('user_name', $newUserName)->exists()) {
            return ['code' => '-304', 'status' => 'usuario ya existe'];
        }

        $newEmail = $p->get('email', false);
        if ($newEmail !== false && $newEmail !== $user->email
            && User::where('email', $newEmail)->exists()) {
            return ['code' => '-304', 'status' => 'usuario ya existe'];
        }

        $updates = [];
        foreach (['fullName' => 'full_name', 'userName' => 'user_name', 'email' => 'email',
            'about' => 'about', 'country' => 'country', 'status' => 'status',
            'avatar' => 'avatar'] as $param => $column) {
            $value = $p->get($param, false);
            if ($value !== false) {
                $updates[$column] = $param === 'userName' ? trim((string) $value) : (string) $value;
            }
        }

        if (($pwd = $p->get('pwd', '')) !== '') {
            $updates['password'] = $this->auth->hash((string) $pwd);
            $updates['legacy_md5'] = null;
        }

        $user->update($updates);

        return ['code' => 1, 'status' => 'registrado con exito'];
    }

    /**
     * Shape de fila `users` legacy (golden 43): claves y tipos exactos.
     * pwd va vacío deliberadamente (antes exponía el hash).
     */
    private function legacyShape(User $user): array
    {
        return [
            'idUser' => (string) $user->id,
            'fullName' => (string) $user->full_name,
            'userName' => (string) $user->user_name,
            'email' => (string) $user->email,
            'about' => (string) $user->about,
            'country' => (string) $user->country,
            'status' => (string) $user->status,
            'timestamp' => (string) $user->legacy_timestamp,
            'lastAccess' => (string) $user->last_access,
            'pwd' => '',
            'avatar' => (string) $user->avatar,
            'settings' => (string) $user->settings,
        ];
    }
}
