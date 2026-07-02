<?php

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Document
 */
class DocumentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'clave' => $this->clave,
            'consecutivo' => $this->consecutivo,
            'tipo_documento' => $this->tipo_documento,
            'estado' => $this->estado,
            'environment' => $this->env,
            'fecha_creacion' => optional($this->fecha_creacion)->toIso8601String(),
        ];
    }
}
