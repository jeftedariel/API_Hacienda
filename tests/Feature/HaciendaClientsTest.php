<?php

use Illuminate\Support\Facades\Http;

/**
 * Cubre de forma determinista el mapeo legacy de los clientes de Hacienda
 * (los golden masters 52-58 golpean los endpoints reales y solo corren con
 * HACIENDA_LIVE_TESTS=1). Las respuestas falsas replican las capturadas.
 */
test('send devuelve HTTP 400 con los headers crudos de Hacienda en text[]', function () {
    Http::fake([
        'api-sandbox.comprobanteselectronicos.go.cr/*' => Http::response(
            '{"message":"Unauthorized"}',
            401,
            ['content-type' => 'application/json', 'x-amzn-errortype' => 'UnauthorizedException'],
        ),
    ]);

    $response = $this->post('/api.php', [
        'w' => 'send', 'r' => 'json', 'token' => 'fake-token',
        'clave' => '50620032400310123456700100001010000000017100000017',
        'fecha' => '2024-02-07T12:00:00-06:00',
        'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '3101234567',
        'comprobanteXml' => base64_encode('<xml/>'), 'client_id' => 'api-stag',
    ]);

    // Contrato legacy: Status de Hacienda != 'ok'/'error' => HTTP 400 fijo,
    // status error, y text = explode("\n", headers crudos + body).
    $response->assertStatus(400);
    $json = $response->json();
    expect($json['status'])->toBe('error');
    expect($json['resp'])->toBeArray();
    expect($json['resp'][0])->toStartWith('HTTP/');
    expect($json['resp'][0])->toContain('401');
    expect(end($json['resp']))->toBe('{"message":"Unauthorized"}');

    Http::assertSent(function ($request) {
        $body = json_decode($request->body(), true);

        return $request->hasHeader('Authorization', 'bearer fake-token')
            && $body['clave'] === '50620032400310123456700100001010000000017100000017'
            && ! array_key_exists('callbackUrl', $body)     // vacío => se omite
            && ! array_key_exists('receptor', $body);       // sin receptor => se omite
    });
});

test('send con receptor y callbackUrl los incluye en el payload', function () {
    Http::fake(['*' => Http::response('', 202)]);

    $this->post('/api.php', [
        'w' => 'send', 'r' => 'json', 'token' => 't',
        'clave' => 'c', 'fecha' => 'f',
        'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '310',
        'recp_tipoIdentificacion' => '02', 'recp_numeroIdentificacion' => '206',
        'comprobanteXml' => 'x', 'client_id' => 'api-stag',
        'callbackUrl' => 'https://mi.app/callback',
    ]);

    Http::assertSent(function ($request) {
        $body = json_decode($request->body(), true);

        return $body['receptor'] === ['tipoIdentificacion' => '02', 'numeroIdentificacion' => '206']
            && $body['callbackUrl'] === 'https://mi.app/callback';
    });
});

test('sendMensaje rellena consecutivoReceptor a 20 dígitos', function () {
    Http::fake(['*' => Http::response('', 202)]);

    $this->post('/api.php', [
        'w' => 'send', 'r' => 'sendMensaje', 'token' => 't',
        'clave' => 'c', 'fecha' => 'f',
        'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '310',
        'recp_tipoIdentificacion' => '02', 'recp_numeroIdentificacion' => '206',
        'consecutivoReceptor' => '7', 'comprobanteXml' => 'x', 'client_id' => 'api-stag',
    ]);

    Http::assertSent(fn ($request) => json_decode($request->body(), true)['consecutivoReceptor'] === '00000000000000000007');
});

test('token devuelve la respuesta del IDP decodificada', function () {
    Http::fake([
        'idp.comprobanteselectronicos.go.cr/*' => Http::response(
            '{"error":"invalid_grant","error_description":"Invalid user credentials"}',
            401,
        ),
    ]);

    $response = $this->post('/api.php', [
        'w' => 'token', 'r' => 'gettoken', 'grant_type' => 'password',
        'client_id' => 'api-stag', 'username' => 'fake@fake.com', 'password' => 'bad',
    ]);

    // Contrato legacy: el body del IDP se devuelve con status ok y HTTP 200
    // aunque el IDP haya respondido 401.
    $response->assertStatus(200)->assertExactJson([
        'status' => 'ok',
        'resp' => ['error' => 'invalid_grant', 'error_description' => 'Invalid user credentials'],
    ]);

    Http::assertSent(fn ($request) => $request['grant_type'] === 'password'
        && $request['client_id'] === 'api-stag'
        && str_contains($request->url(), 'realms/rut-stag'));
});

test('token usa el realm de producción con api-prod', function () {
    Http::fake(['*' => Http::response('{}', 200)]);

    $this->post('/api.php', [
        'w' => 'token', 'r' => 'gettoken', 'grant_type' => 'password',
        'client_id' => 'api-prod', 'username' => 'u', 'password' => 'p',
    ]);

    Http::assertSent(fn ($request) => str_contains($request->url(), 'realms/rut/'));
});

test('consultar devuelve el estado decodificado de Hacienda', function () {
    Http::fake([
        'api-sandbox.comprobanteselectronicos.go.cr/*' => Http::response(
            '{"clave":"506...","ind-estado":"aceptado","respuesta-xml":"PE1IL3=="}',
            200,
        ),
    ]);

    $response = $this->post('/api.php', [
        'w' => 'consultar', 'r' => 'consultarCom',
        'clave' => '50620032400310123456700100001010000000017100000017',
        'token' => 'tok', 'client_id' => 'api-stag',
    ]);

    $response->assertStatus(200);
    expect($response->json('status'))->toBe('ok');
    expect($response->json('resp.ind-estado'))->toBe('aceptado');

    Http::assertSent(fn ($request) => $request->method() === 'GET'
        && str_ends_with($request->url(), '/recepcion/50620032400310123456700100001010000000017100000017')
        && $request->hasHeader('Authorization', 'Bearer tok'));
});

test('la verificación SSL hacia Hacienda está activa por defecto', function () {
    expect(config('hacienda.verify_ssl'))->toBeTrue();
});
