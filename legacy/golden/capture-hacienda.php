#!/usr/bin/env php
<?php
/**
 * Captura golden masters de los módulos que llaman a Hacienda (token, send,
 * consultar, callback) usando credenciales FALSAS contra los endpoints
 * públicos de sandbox: las respuestas de error (400/401) son deterministas
 * y pinnean el shape exacto que el legacy devuelve a los clientes
 * (incluidos los headers HTTP crudos dentro de `text`).
 *
 * Requiere el stack docker de /legacy arriba y salida a internet.
 * Uso: php capture-hacienda.php [baseUrl] [outDir]
 */

require __DIR__.'/capture-lib.php';

$baseUrl = $argv[1] ?? 'http://localhost:8080/api.php';
$outDir  = $argv[2] ?? dirname(__DIR__, 2).'/tests/Fixtures/golden';

$cases = [
    ['name' => 'token-fake-creds', 'compare' => 'shape',
     'notes' => 'Credenciales falsas contra el IDP sandbox real. El legacy hace json_decode sobre headers+body (CURLOPT_HEADER truthy) => resp null.',
     'params' => ['w' => 'token', 'r' => 'gettoken', 'grant_type' => 'password',
                  'client_id' => 'api-stag', 'username' => 'fake@fake.com', 'password' => 'fakepwd']],
    ['name' => 'token-bad-client-id', 'compare' => 'shape',
     'notes' => 'client_id desconocido => url null.',
     'params' => ['w' => 'token', 'r' => 'gettoken', 'grant_type' => 'password',
                  'client_id' => 'api-nope', 'username' => 'x', 'password' => 'y']],
    ['name' => 'token-refresh-fake', 'compare' => 'shape',
     'params' => ['w' => 'token', 'r' => 'refresh', 'grant_type' => 'refresh_token',
                  'client_id' => 'api-stag', 'refresh_token' => 'fake-refresh-token']],
    ['name' => 'send-fake-token', 'compare' => 'send-raw',
     'notes' => 'Token falso contra recepción sandbox real => Status 401 y headers crudos en text[].',
     'params' => ['w' => 'send', 'r' => 'json', 'token' => 'fake-token',
                  'clave' => '50620032400310123456700100001010000000017100000017',
                  'fecha' => '2024-02-07T12:00:00-06:00',
                  'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '3101234567',
                  'recp_tipoIdentificacion' => '02', 'recp_numeroIdentificacion' => '206540123',
                  'comprobanteXml' => base64_encode('<xml/>'), 'client_id' => 'api-stag']],
    ['name' => 'send-te-fake-token', 'compare' => 'send-raw',
     'params' => ['w' => 'send', 'r' => 'sendTE', 'token' => 'fake-token',
                  'clave' => '50620032400310123456700100001040000000021100000021',
                  'fecha' => '2024-02-07T12:00:00-06:00',
                  'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '3101234567',
                  'comprobanteXml' => base64_encode('<xml/>'), 'client_id' => 'api-stag']],
    ['name' => 'send-mensaje-fake-token', 'compare' => 'send-raw',
     'params' => ['w' => 'send', 'r' => 'sendMensaje', 'token' => 'fake-token',
                  'clave' => '50620032400310123456700100001010000000017100000017',
                  'fecha' => '2024-02-07T12:00:00-06:00',
                  'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '3101234567',
                  'recp_tipoIdentificacion' => '02', 'recp_numeroIdentificacion' => '206540123',
                  'consecutivoReceptor' => '1', 'comprobanteXml' => base64_encode('<xml/>'),
                  'client_id' => 'api-stag']],
    ['name' => 'consultar-fake-token', 'compare' => 'shape',
     'notes' => 'GET de estado con token falso contra sandbox real.',
     'params' => ['w' => 'consultar', 'r' => 'consultarCom', 'token' => 'fake-token',
                  'clave' => '50620032400310123456700100001010000000017100000017',
                  'client_id' => 'api-stag']],
    ['name' => 'consultar-bad-client-id', 'compare' => 'exact',
     'params' => ['w' => 'consultar', 'r' => 'consultarCom', 'token' => 'x',
                  'clave' => '123', 'client_id' => 'api-nope']],
    ['name' => 'callback-hacienda', 'compare' => 'exact',
     'notes' => 'Callback asíncrono de Hacienda: w/r por query, cuerpo JSON crudo.',
     'method' => 'POST', 'bodyMode' => 'rawjson',
     'query' => ['w' => 'callback', 'r' => 'callback'],
     'rawBody' => json_encode([
         'clave' => '50620032400310123456700100001010000000017100000017',
         'fecha' => '2024-02-07T12:05:00-06:00',
         'ind-estado' => 'aceptado',
         'respuesta-xml' => base64_encode('<MensajeHacienda/>'),
     ])],
];

$seq = 52;
foreach ($cases as $case) {
    $resp = callApi($baseUrl, $case);
    saveFixture($outDir, $seq++, $case, $resp);
}

echo "\nListo.\n";
