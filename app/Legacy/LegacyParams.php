<?php

namespace App\Legacy;

use Illuminate\Http\Request;

/**
 * Réplica del estado de parámetros del legacy (api/core/params.php) y de la
 * resolución de entrada del front controller (www/api.php).
 *
 * Orden de resolución (exacto al legacy):
 *   1. Si $_GET trae 'w'  -> SOLO los parámetros GET.
 *   2. Si $_POST trae 'w' -> SOLO los parámetros POST (form o multipart).
 *   3. Cuerpo crudo: si es JSON válido con 'w' -> los parámetros del JSON.
 *      Si el cuerpo no es JSON válido (incluye cuerpo vacío) -> muere con
 *      "La informacion json enviada contiene errores." (texto plano, HTTP 200).
 */
class LegacyParams
{
    /** @var array<string, mixed> */
    private array $params = [];

    public const INVALID_JSON_MESSAGE = 'La informacion json enviada contiene errores.';

    private function __construct(array $params)
    {
        $this->params = $params;
    }

    public static function fromArray(array $params): self
    {
        return new self($params);
    }

    /**
     * @throws LegacyDieException si la petición replica el die() del legacy.
     */
    public static function fromRequest(Request $request): self
    {
        $get = $request->query->all();
        if (isset($get['w'])) {
            return new self($get);
        }

        // $_POST: form-urlencoded o multipart
        $post = $request->request->all();
        if (isset($post['w'])) {
            return new self($post);
        }

        // Cuerpo crudo (php://input). Para multipart PHP lo deja vacío.
        $content = (string) $request->getContent();
        $decoded = json_decode($content, true);
        if (is_string($content) && is_array($decoded) && json_last_error() === JSON_ERROR_NONE) {
            if (isset($decoded['w'])) {
                return new self($decoded);
            }

            // JSON válido sin 'w': el legacy continúa sin parámetros y cae
            // en el módulo por defecto ('cala').
            return new self([]);
        }

        throw new LegacyDieException(self::INVALID_JSON_MESSAGE);
    }

    /**
     * params_get(): un valor vacío tras trim equivale a ausente.
     */
    public function get(string $key, mixed $def = false): mixed
    {
        if (! array_key_exists($key, $this->params)) {
            return $def;
        }

        $value = $this->params[$key];

        // El legacy hace trim() sobre el valor; con arrays (posibles vía JSON)
        // trim() devolvía null en PHP 7.4 => se consideraba ausente.
        if (is_array($value)) {
            return $def;
        }

        return trim((string) $value) !== '' ? $value : $def;
    }

    public function set(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->params;
    }
}
