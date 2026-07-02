<?php

namespace App\Http\Requests\Api\V1;

use App\Services\Emission\DocumentEmissionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Solicitud de emisión de un comprobante electrónico. El emisor sale de la
 * empresa autenticada; el cliente envía el tipo, el ambiente y los datos del
 * documento (receptor, detalles, medios de pago, totales, referencias…).
 */
class EmitDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tipo' => ['required', 'string', Rule::in(DocumentEmissionService::supportedTypes())],
            'environment' => ['sometimes', Rule::in(['stag', 'prod'])],

            // Datos del documento (mismas claves que el generador v4.4).
            'fecha_emision' => ['sometimes', 'string'],
            'condicion_venta' => ['required_unless:tipo,MR', 'string'],
            'medios_pago' => ['sometimes'],
            'detalles' => ['required_unless:tipo,MR'],
            'total_ventas' => ['required_unless:tipo,MR'],
            'total_ventas_neta' => ['required_unless:tipo,MR'],
            'total_comprobante' => ['required_unless:tipo,MR'],

            // Receptor (opcional en TE / omitible).
            'receptor_nombre' => ['sometimes', 'string'],
            'receptor_tipo_identif' => ['sometimes', 'string'],
            'receptor_num_identif' => ['sometimes', 'string'],
            'receptor_email' => ['sometimes', 'email'],

            // Escape hatch: parámetros adicionales del generador legacy.
            'params' => ['sometimes', 'array'],
        ];
    }

    /**
     * Reúne los parámetros del documento para el generador (todo menos los
     * campos de control), fusionando el escape hatch `params`.
     *
     * @return array<string, mixed>
     */
    public function documentParams(): array
    {
        $data = $this->except(['tipo', 'environment', 'params']);
        $data = $this->flattenJson($data);

        return array_merge($data, $this->input('params', []));
    }

    /**
     * detalles/medios_pago/otrosCargos pueden llegar como arrays JSON; el
     * generador legacy espera strings JSON, así que se recodifican.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function flattenJson(array $data): array
    {
        foreach (['detalles', 'medios_pago', 'otrosCargos', 'informacion_referencia', 'otros'] as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                $data[$key] = json_encode($data[$key]);
            }
        }

        return $data;
    }
}
