<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Support\ActingCompany;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Inventario de productos por sucursal (ex
 * `<id>_master_inventary_sucursal_NNN`).
 */
class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        return response()->json(
            Product::where('company_id', $company->id)
                ->when($request->query('sucursal'), fn ($q, $s) => $q->where('sucursal', $s))
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null, 403, 'Sin empresa asociada.');

        $data = $request->validate([
            'sucursal' => ['required', 'string', 'max:10'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['sometimes', 'string', 'max:255'],
            'unidad_medida' => ['required', 'string', 'max:20'],
            'precio_venta' => ['required', 'numeric'],
            'id_impuesto' => ['sometimes', 'integer'],
            'cantidad_impuesto' => ['sometimes', 'integer'],
            'codigo_barras' => ['required', 'string', 'max:100'],
            'disponible' => ['sometimes', 'integer'],
        ]);
        $data['company_id'] = $company->id;

        return response()->json(Product::create($data), 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $this->authorize($request, $product);
        $product->update($request->only([
            'nombre', 'descripcion', 'unidad_medida', 'precio_venta',
            'id_impuesto', 'cantidad_impuesto', 'codigo_barras', 'disponible',
        ]));

        return response()->json($product);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $this->authorize($request, $product);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado.']);
    }

    private function authorize(Request $request, Product $product): void
    {
        $company = ActingCompany::for($request->user());
        abort_if($company === null || $product->company_id !== $company->id, 403, 'No autorizado.');
    }
}
