<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Support\ActingCompany;
use App\Models\HaciendaCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    private function companyId(Request $request): int
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        return $company->id;
    }
}
