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
    'unknown-route',
    'no-params-at-all',
    'invalid-json-body',
    'ejemplo-hola',

    // Fase 2: núcleo FE
    'clave-fe-fisico',
    'clave-te-juridico',
    'clave-nc-contingencia',
    'clave-nd-sininternet',
    'clave-missing-param',
    'genxml-test',
    'genxml-fe-full',
    'genxml-fe-minimal',
    'genxml-fe-json-body',
    'genxml-fe-missing-clave',
    'genxml-nc-full',
    'genxml-nd-full',
    'genxml-te-omitir-receptor',
    'genxml-te-con-receptor',
    'genxml-fec',
    'genxml-fee',
    'genxml-mr-aceptado',
    'genxml-mr-rechazado',
    'genxml-mr-missing-param',
    'makejson',
    'makejson-missing-param',
    'xmltobase64-bad-code',
    'makeqr',
    'firmar-fe',
    'firmar-bad-pin',
    'firmar-missing-p12',
];

/**
 * Fixtures cuyo comportamiento legacy era un bug irreproducible y se decidió
 * divergir deliberadamente (documentado en el módulo correspondiente).
 */
const INTENTIONALLY_DIVERGENT = [
    // El módulo check legacy cargaba un fac.xml hardcodeado contra XSD 4.2 y
    // volcaba warnings HTML; se reimplementó validando contra XSD v4.4.
    'check-xml-fe' => 'CheckModule reimplementado (legacy roto); ver app/Legacy/Modules/CheckModule.php',
];

/**
 * Normalizadores por fixture: neutralizan las partes de la respuesta que
 * dependen del momento de ejecución (documentado en cada caso).
 *
 * La clave numérica lleva la fecha local ddmmyy en las posiciones 4-9;
 * el resto es determinista.
 */
const NORMALIZERS = [
    'clave-fe-fisico' => 'normalizeClaveDate',
    'clave-te-juridico' => 'normalizeClaveDate',
    'clave-nc-contingencia' => 'normalizeClaveDate',
    'clave-nd-sininternet' => 'normalizeClaveDate',
];

function normalizeClaveDate(string $body): string
{
    return preg_replace('/("clave":")(\d{3})\d{6}/', '$1$2DDMMYY', $body);
}

/**
 * Modo signature: shape del JSON + verificación criptográfica del XML
 * firmado (la firma incluye SigningTime e IDs aleatorios, no hay paridad
 * byte a byte posible).
 */
function assertSignatureMatches(string $expectedBody, string $actualBody, string $context): void
{
    assertShapeMatches($expectedBody, $actualBody, $context);

    $actual = json_decode($actualBody, true);
    $signed = base64_decode($actual['resp']['xmlFirmado'] ?? '', true);
    expect($signed)->not->toBeFalse("[$context] xmlFirmado no es base64");

    $result = Tests\Support\XmlSignatureVerifier::verify((string) $signed);
    expect($result['ok'])->toBeTrue("[$context] firma inválida: ".implode('; ', $result['errors']));
}

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
        if (isset(INTENTIONALLY_DIVERGENT[$fixture->name])) {
            $this->markTestSkipped('Divergencia deliberada: '.INTENTIONALLY_DIVERGENT[$fixture->name]);
        }

        if (! in_array($fixture->name, IMPLEMENTED, true)) {
            $this->markTestSkipped("Módulo aún no portado: {$fixture->name}");
        }

        // Los fixtures de firma referencian el p12 de prueba por downloadCode;
        // el FileStorageService provisional lo resuelve en storage/app/legacy-files.
        if (isset($fixture->params['p12Url']) && $fixture->params['p12Url'] !== '') {
            $dir = storage_path('app/legacy-files');
            @mkdir($dir, 0775, true);
            copy(base_path('legacy/golden/test-cert.p12'), $dir.'/'.$fixture->params['p12Url']);
        }

        $response = callFixture($fixture);

        expect($response->getStatusCode())->toBe(
            $fixture->expectedStatus,
            "[{$fixture->name}] HTTP status difiere. Body: ".substr($response->getContent(), 0, 300)
        );

        $normalize = NORMALIZERS[$fixture->name] ?? null;
        $expectedBody = $normalize ? $normalize($fixture->expectedBody) : $fixture->expectedBody;
        $actualBody = $normalize ? $normalize($response->getContent()) : $response->getContent();

        match ($fixture->compare) {
            'exact', 'json-exact' => expect($actualBody)->toBe($expectedBody, "[{$fixture->name}] body difiere"),
            'shape' => assertShapeMatches($fixture->expectedBody, $response->getContent(), $fixture->name),
            'signature' => assertSignatureMatches($fixture->expectedBody, $response->getContent(), $fixture->name),
            'status-only' => null,
            default => throw new RuntimeException("Modo compare desconocido: {$fixture->compare}"),
        };
    });
}
