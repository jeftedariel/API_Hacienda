<?php

use Tests\Parity\GoldenFixture;

/**
 * Tests de paridad contra los golden masters capturados del API legacy
 * (tests/Fixtures/golden/, ver legacy/golden/README.md).
 *
 * Cada fixture se reproduce contra /api.php y se compara según su modo:
 *   exact  -> status + body byte a byte
 *   shape  -> status + estructura del JSON (valores aleatorios excluidos)
 *   signature -> status + shape; la validez criptográfica se prueba aparte
 *
 * IMPLEMENTED lista los fixtures cuyos módulos ya fueron portados; el resto
 * se marca como skipped para que el progreso del port sea visible sin
 * romper la suite.
 */
const IMPLEMENTED = [
    // Fase 1: tubería de compatibilidad
    'version',
    'version-get',
    'unknown-module',
    // 'unknown-route' usa w=clave: entra con el port del módulo clave (Fase 2)
    'no-params-at-all',
    'invalid-json-body',
    'ejemplo-hola',
];

function callFixture(GoldenFixture $f): Illuminate\Testing\TestResponse
{
    $test = test();

    if ($f->bodyMode === 'rawjson') {
        return $test->call(
            'POST',
            '/api.php',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $f->rawBody,
        );
    }

    return $test->call($f->method, '/api.php', $f->params ?? []);
}

function assertShapeMatches(string $expectedBody, string $actualBody, string $context): void
{
    $expected = json_decode($expectedBody, true);
    $actual = json_decode($actualBody, true);

    expect($actual)->not->toBeNull("[$context] la respuesta no es JSON: ".substr($actualBody, 0, 200));
    expect(shapeOf($actual))->toBe(shapeOf($expected), "[$context] la estructura del JSON difiere");

    // El campo status sí debe coincidir exactamente.
    if (is_array($expected) && array_key_exists('status', $expected)) {
        expect($actual['status'] ?? null)->toBe($expected['status'], "[$context] status difiere");
    }
}

/** Estructura recursiva de claves y tipos, ignorando valores. */
function shapeOf(mixed $value): mixed
{
    if (! is_array($value)) {
        return gettype($value);
    }

    $shape = [];
    foreach ($value as $k => $v) {
        $shape[$k] = shapeOf($v);
    }
    ksort($shape);

    return $shape;
}

foreach (GoldenFixture::all() as $name => $fixture) {
    test("paridad legacy: {$fixture->file}", function () use ($fixture) {
        if (! in_array($fixture->name, IMPLEMENTED, true)) {
            $this->markTestSkipped("Módulo aún no portado: {$fixture->name}");
        }

        $response = callFixture($fixture);

        expect($response->getStatusCode())->toBe(
            $fixture->expectedStatus,
            "[{$fixture->name}] HTTP status difiere. Body: ".substr($response->getContent(), 0, 300)
        );

        match ($fixture->compare) {
            'exact', 'json-exact' => expect($response->getContent())->toBe($fixture->expectedBody, "[{$fixture->name}] body difiere"),
            'shape', 'signature' => assertShapeMatches($fixture->expectedBody, $response->getContent(), $fixture->name),
            'status-only' => null,
            default => throw new RuntimeException("Modo compare desconocido: {$fixture->compare}"),
        };
    });
}
