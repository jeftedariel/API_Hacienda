<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Support\ActingCompany;
use App\Models\Branch;
use App\Models\Terminal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Sucursales y terminales de la empresa (ex `<id>_master_sucursales` y
 * `<id>_master_terminales`).
 */
class BranchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->company($request)->branches ?? Branch::where('company_id', $this->companyId($request))->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre_sucursal' => ['required', 'string', 'max:255'],
            'sucursal' => ['required', 'string', 'max:10'],
        ]);
        $data['company_id'] = $this->companyId($request);

        return response()->json(Branch::create($data), 201);
    }

    public function terminals(Request $request): JsonResponse
    {
        return response()->json(
            Terminal::where('company_id', $this->companyId($request))
                ->when($request->query('branch_id'), fn ($q, $b) => $q->where('branch_id', $b))
                ->get()
        );
    }

    public function storeTerminal(Request $request): JsonResponse
    {
        $companyId = $this->companyId($request);
        $data = $request->validate([
            'nombre_terminal' => ['required', 'string', 'max:255'],
            'terminal' => ['required', 'string', 'max:10'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
        ]);
        $data['company_id'] = $companyId;

        return response()->json(Terminal::create($data), 201);
    }

    private function company(Request $request)
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        return $company;
    }

    private function companyId(Request $request): int
    {
        return $this->company($request)->id;
    }
}
