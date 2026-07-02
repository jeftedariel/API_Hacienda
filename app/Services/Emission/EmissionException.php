<?php

namespace App\Services\Emission;

/**
 * Error de negocio durante la emisión de un comprobante (datos faltantes,
 * certificado ausente, rechazo de Hacienda, etc.).
 */
class EmissionException extends \RuntimeException {}
