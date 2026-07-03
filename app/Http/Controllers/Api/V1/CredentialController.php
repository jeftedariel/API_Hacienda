<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Support\ActingCompany;
use App\Models\HaciendaCredential;
use App\Models\User;
use App\Services\Files\FileStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Credenciales ATV de Hacienda por ambiente. La contraseña y el PIN se
 * guardan cifrados (cast encrypted) y nunca se devuelven en las respuestas.
 */
class CredentialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $companyId = $this->companyId($request);

        $creds = HaciendaCredential::where('company_id', $companyId)->get()
            ->map(fn ($c) => [
                'environment' => $c->environment,
                'username' => $c->username,
                'p12_download_code' => $c->p12_download_code,
                'has_pin' => $c->pin !== null && $c->pin !== '',
            ]);

        return response()->json($creds);
    }

    public function upsert(Request $request): JsonResponse
    {
        $companyId = $this->companyId($request);

        $data = $request->validate([
            'environment' => ['required', 'in:stag,prod'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'p12_download_code' => ['sometimes', 'string'],
            'pin' => ['sometimes', 'string'],
        ]);

        $cred = HaciendaCredential::updateOrCreate(
            ['company_id' => $companyId, 'environment' => $data['environment']],
            [
                'username' => $data['username'],
                'password' => $data['password'],
                'p12_download_code' => $data['p12_download_code'] ?? null,
                'pin' => $data['pin'] ?? null,
            ]
        );

        return response()->json([
            'environment' => $cred->environment,
            'username' => $cred->username,
            'p12_download_code' => $cred->p12_download_code,
            'has_pin' => $cred->pin !== null && $cred->pin !== '',
        ], 201);
    }

    /**
     * Sube el certificado .p12 del emisor y lo vincula a la credencial del
     * ambiente indicado (crea la credencial si aún no existe).
     */
    public function uploadCertificate(Request $request, FileStorageService $files): JsonResponse
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        $data = $request->validate([
            'environment' => ['required', 'in:stag,prod'],
            'certificate' => ['required', 'file'],
            'pin' => ['sometimes', 'string'],
        ]);

        $owner = $request->user() instanceof User ? $request->user() : $company->owner;

        $result = $files->upload(
            $owner,
            $request->file('certificate'),
            type: 'hacienda',
            ext: 'p12,P12,pfx,PFX',
        );

        if (is_string($result)) {
            $message = match ($result) {
                FileStorageService::ERROR_TOO_BIG => 'El certificado supera el tamaño máximo permitido.',
                FileStorageService::ERROR_EXT_NOT_ALLOWED => 'El certificado debe ser un archivo .p12 o .pfx.',
                default => 'No se pudo guardar el certificado.',
            };

            throw ValidationException::withMessages(['certificate' => $message]);
        }

        $cred = HaciendaCredential::updateOrCreate(
            ['company_id' => $company->id, 'environment' => $data['environment']],
            array_filter([
                'p12_download_code' => $result['downloadCode'],
                'pin' => $data['pin'] ?? null,
            ], fn ($value) => $value !== null),
        );

        return response()->json([
            'environment' => $cred->environment,
            'username' => $cred->username,
            'p12_download_code' => $cred->p12_download_code,
            'has_pin' => $cred->pin !== null && $cred->pin !== '',
        ], 201);
    }

    private function companyId(Request $request): int
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        return $company->id;
    }
}
