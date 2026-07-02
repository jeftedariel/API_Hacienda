<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmitDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Support\ActingCompany;
use App\Models\Document;
use App\Services\Emission\DocumentEmissionService;
use App\Services\Emission\EmissionException;
use App\Services\Hacienda\StatusClient;
use App\Services\Hacienda\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Emisión y seguimiento de comprobantes electrónicos (recurso principal de
 * la API v1). El emisor es la empresa del token; el flujo completo
 * (clave → XML → firma → token → envío → persistencia) lo ejecuta
 * DocumentEmissionService.
 */
class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentEmissionService $emission,
        private readonly StatusClient $status,
    ) {}

    /**
     * Lista los comprobantes de la empresa autenticada.
     */
    public function index(Request $request): JsonResponse
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        $docs = Document::where('company_id', $company->id)
            ->when($request->query('environment'), fn ($q, $env) => $q->where('env', $env === 'prod' ? 'api-prod' : 'api-stag'))
            ->when($request->query('tipo'), fn ($q, $t) => $q->where('tipo_documento', $t))
            ->orderByDesc('id')
            ->paginate((int) $request->integer('per_page', 25));

        return DocumentResource::collection($docs)->response();
    }

    /**
     * Emite un nuevo comprobante electrónico.
     */
    public function store(EmitDocumentRequest $request): JsonResponse
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        try {
            $result = $this->emission->emit(
                company: $company,
                tipo: $request->string('tipo')->value(),
                documentParams: $request->documentParams(),
                environment: $request->string('environment')->value() ?: ($company->env === 'api-prod' ? 'prod' : 'stag'),
            );
        } catch (EmissionException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($result, 201);
    }

    /**
     * Muestra un comprobante de la empresa.
     */
    public function show(Request $request, Document $document): JsonResponse
    {
        $this->authorizeCompany($request, $document);

        return DocumentResource::make($document)->response();
    }

    /**
     * Consulta el estado del comprobante en Hacienda y lo actualiza.
     */
    public function status(Request $request, Document $document): JsonResponse
    {
        $this->authorizeCompany($request, $document);

        $company = ActingCompany::for($request->user());
        $environment = $document->env === 'api-prod' ? 'prod' : 'stag';
        $credential = $company->credentials()->where('environment', $environment)->first();
        abort_if($credential === null, 422, 'Sin credenciales para consultar.');

        $token = $this->tokenFor($credential, $document->env);

        $result = $this->status->consultar($document->clave, $document->env, (string) $token);

        if (is_object($result) && isset($result->{'ind-estado'})) {
            $document->update(['estado' => $result->{'ind-estado'}]);
        }

        return response()->json(['document' => DocumentResource::make($document), 'hacienda' => $result]);
    }

    private function authorizeCompany(Request $request, Document $document): void
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null || $document->company_id !== $company->id, 403, 'No autorizado.');
    }

    private function tokenFor($credential, string $clientId): ?string
    {
        $response = app(TokenService::class)->requestToken([
            'client_id' => $clientId,
            'grant_type' => 'password',
            'username' => (string) $credential->username,
            'password' => (string) $credential->password,
        ]);

        return is_object($response) ? ($response->access_token ?? null) : null;
    }
}
