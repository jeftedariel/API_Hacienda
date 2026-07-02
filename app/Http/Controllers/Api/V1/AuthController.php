<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CompanyLoginRequest;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Support\ActingCompany;
use App\Models\CompanyUser;
use App\Services\Auth\CompanyUserAuthService;
use App\Services\Auth\LegacyAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Autenticación de la API REST v1 mediante tokens Sanctum.
 *
 * Emite dos tipos de token, distinguidos por su ability:
 *  - "master": usuario de plataforma (dueño de una empresa).
 *  - "company": sub-usuario de una empresa.
 *
 * Reutiliza la verificación de contraseñas legacy (bcrypt + fallback md5 con
 * rehash transparente), de modo que las credenciales migradas funcionan sin
 * que el usuario tenga que restablecerlas.
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly LegacyAuthService $masterAuth,
        private readonly CompanyUserAuthService $companyAuth,
    ) {}

    /**
     * Login de usuario master (dueño de empresa).
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->masterAuth->loadByUserNameOrEmail($request->string('username')->value());

        if ($user === null || ! $this->masterAuth->verifyPassword($user, $request->string('password')->value())) {
            throw ValidationException::withMessages(['username' => 'Credenciales inválidas.']);
        }

        $token = $user->createToken(
            $request->string('device_name')->value() ?: 'api',
            ['master']
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'principal' => 'master',
            'user' => [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'company_id' => ActingCompany::idFor($user),
            ],
        ]);
    }

    /**
     * Login de sub-usuario de una empresa.
     */
    public function companyLogin(CompanyLoginRequest $request): JsonResponse
    {
        $companyId = $request->integer('company_id');
        $user = $this->companyAuth->loadByUserNameOrEmail($companyId, $request->string('username')->value());

        if ($user === null || ! $this->companyAuth->verifyPassword($user, $request->string('password')->value())) {
            throw ValidationException::withMessages(['username' => 'Credenciales inválidas.']);
        }

        $token = $user->createToken(
            $request->string('device_name')->value() ?: 'api',
            ['company']
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'principal' => 'company',
            'user' => [
                'id' => $user->id,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'company_id' => $companyId,
            ],
        ]);
    }

    /**
     * Datos del principal autenticado.
     */
    public function me(Request $request): JsonResponse
    {
        $principal = $request->user();

        return response()->json([
            'principal' => $principal instanceof CompanyUser ? 'company' : 'master',
            'id' => $principal->id,
            'user_name' => $principal->user_name,
            'email' => $principal->email,
            'company_id' => ActingCompany::idFor($principal),
            'abilities' => $request->user()->currentAccessToken()->abilities ?? [],
        ]);
    }

    /**
     * Revoca el token actual (logout).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada.']);
    }
}
