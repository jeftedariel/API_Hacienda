<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Support\ActingCompany;
use App\Models\Receiver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Receptores/clientes de la empresa (ex `<id>_master_receiver`).
 */
class ReceiverController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->scope($request)->where('estado_cliente', '1')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        $data = $this->validated($request);
        $data['company_id'] = $company->id;
        $data['estado_cliente'] = '1';

        $receiver = Receiver::create($data);

        return response()->json($receiver, 201);
    }

    public function show(Request $request, Receiver $receiver): JsonResponse
    {
        $this->authorize($request, $receiver);

        return response()->json($receiver);
    }

    public function update(Request $request, Receiver $receiver): JsonResponse
    {
        $this->authorize($request, $receiver);
        $receiver->update($this->validated($request));

        return response()->json($receiver);
    }

    public function destroy(Request $request, Receiver $receiver): JsonResponse
    {
        $this->authorize($request, $receiver);
        $receiver->update(['estado_cliente' => '0']); // borrado lógico, como el legacy

        return response()->json(['message' => 'Receptor desactivado.']);
    }

    private function scope(Request $request)
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        return Receiver::where('company_id', $company->id);
    }

    private function authorize(Request $request, Receiver $receiver): void
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null || $receiver->company_id !== $company->id, 403, 'No autorizado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'nombre_cliente' => ['required', 'string', 'max:100'],
            'numero_cedula' => ['required', 'string', 'max:30'],
            'tipo_cedula' => ['required', 'string', 'max:20'],
            'telefono' => ['sometimes', 'string', 'max:20'],
            'id_provincia' => ['sometimes', 'string', 'max:10'],
            'id_canton' => ['sometimes', 'string', 'max:10'],
            'id_distrito' => ['sometimes', 'string', 'max:10'],
            'id_barrio' => ['sometimes', 'string', 'max:10'],
            'otras_senas' => ['sometimes', 'string', 'max:200'],
            'nombre_comercial' => ['sometimes', 'string', 'max:255'],
            'correo_principal' => ['sometimes', 'email'],
            'copias_correo' => ['sometimes', 'string', 'max:255'],
            'numero_fax' => ['sometimes', 'string', 'max:30'],
        ]);
    }
}
