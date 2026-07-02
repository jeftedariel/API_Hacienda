<?php

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Autenticación de la API v1 con tokens Sanctum (master y company).
 */
function makeMasterUser(string $pwd = 'secret123'): User
{
    return User::create([
        'full_name' => 'Owner', 'user_name' => 'owner', 'email' => 'owner@example.com',
        'about' => '', 'country' => 'crc', 'status' => '1',
        'legacy_timestamp' => time(), 'last_access' => time(),
        'password' => password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 4]),
        'avatar' => '0', 'settings' => null,
    ]);
}

test('login master devuelve un token Sanctum', function () {
    $user = makeMasterUser();
    Company::create(['id' => $user->id, 'owner_user_id' => $user->id, 'nombre' => 'ACME']);

    $response = $this->postJson('/api/v1/auth/login', [
        'username' => 'owner', 'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJsonPath('principal', 'master')
        ->assertJsonPath('user.company_id', $user->id)
        ->assertJsonStructure(['token', 'token_type', 'user' => ['id', 'user_name', 'email']]);
});

test('login master con credenciales inválidas es rechazado', function () {
    makeMasterUser();

    $this->postJson('/api/v1/auth/login', ['username' => 'owner', 'password' => 'mala'])
        ->assertStatus(422);
});

test('login de company user devuelve token con contexto de empresa', function () {
    $owner = makeMasterUser();
    $company = Company::create(['id' => $owner->id, 'owner_user_id' => $owner->id, 'nombre' => 'ACME']);
    CompanyUser::create([
        'company_id' => $company->id, 'full_name' => 'Cajero', 'user_name' => 'cajero',
        'email' => 'cajero@example.com', 'about' => '', 'country' => 'crc', 'status' => '1',
        'legacy_timestamp' => time(), 'last_access' => time(),
        'password' => password_hash('caja123', PASSWORD_BCRYPT, ['cost' => 4]),
        'avatar' => '', 'settings' => null,
    ]);

    $response = $this->postJson('/api/v1/auth/company/login', [
        'company_id' => $company->id, 'username' => 'cajero', 'password' => 'caja123',
    ]);

    $response->assertOk()
        ->assertJsonPath('principal', 'company')
        ->assertJsonPath('user.company_id', $company->id);
});

test('me y logout funcionan con el token', function () {
    $user = makeMasterUser();
    Company::create(['id' => $user->id, 'owner_user_id' => $user->id, 'nombre' => 'ACME']);
    $token = $user->createToken('test', ['master'])->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/auth/me')
        ->assertOk()->assertJsonPath('principal', 'master');

    expect(PersonalAccessToken::count())->toBe(1);

    $this->withToken($token)->postJson('/api/v1/auth/logout')->assertOk();

    // El token quedó revocado en BD (una request nueva en producción daría 401;
    // dentro de un mismo test el guard cachea el usuario ya resuelto).
    expect(PersonalAccessToken::count())->toBe(0);
});

test('un token inexistente es rechazado', function () {
    $this->withToken('999|tokenfalso')->getJson('/api/v1/auth/me')->assertStatus(401);
});

test('rutas protegidas rechazan sin token', function () {
    $this->getJson('/api/v1/company')->assertStatus(401);
    $this->getJson('/api/v1/documents')->assertStatus(401);
});
