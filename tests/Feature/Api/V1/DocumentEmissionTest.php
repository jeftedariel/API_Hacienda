<?php

use App\Models\Company;
use App\Models\HaciendaCredential;
use App\Models\StoredFile;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Illuminate\Support\Facades\Http;

/**
 * Emisión de comprobantes por la API v1: ejercita el flujo completo
 * clave → XML (generador v4.4) → firma XAdES → token → envío → persistencia,
 * con Hacienda mockeado vía Http::fake.
 */
function companyWithCredentials(): array
{
    $user = User::create([
        'full_name' => 'Owner', 'user_name' => 'owner', 'email' => 'owner@example.com',
        'about' => '', 'country' => 'crc', 'status' => '1',
        'legacy_timestamp' => time(), 'last_access' => time(),
        'password' => password_hash('secret123', PASSWORD_BCRYPT, ['cost' => 4]),
        'avatar' => '0', 'settings' => null,
    ]);

    $company = Company::create([
        'id' => $user->id, 'owner_user_id' => $user->id,
        'nombre' => 'ACME S.A.', 'tipo_cedula' => '02', 'cedula' => '3101234567',
        'nombre_comercial' => 'ACME', 'email' => 'facturas@acme.cr',
        'id_provincia' => '1', 'id_canton' => '01', 'id_distrito' => '01', 'id_barrio' => '01',
        'sennas' => 'Oficinas centrales', 'tel_cod_pais' => '506', 'tel_numero' => '22001100',
        'env' => 'api-stag', 'situacion' => 'normal', 'tipo_cambio' => '1',
    ]);

    // Certificado .p12 de prueba (el mismo del corpus golden).
    $dir = storage_path('app/legacy-files/'.$user->id.'/hacienda');
    @mkdir($dir, 0775, true);
    copy(base_path('legacy/golden/test-cert.p12'), $dir.'/cert.p12');

    StoredFile::create([
        'user_id' => $user->id, 'name' => 'cert.p12', 'download_code' => 'p12code',
        'type' => 'hacienda', 'path' => 'legacy-files/'.$user->id.'/hacienda/cert.p12',
    ]);

    HaciendaCredential::create([
        'company_id' => $company->id, 'environment' => 'stag',
        'username' => 'cpf-03-0101-234567@stag.comprobanteselectronicos.go.cr',
        'password' => 'atv-secret', 'p12_download_code' => 'p12code', 'pin' => '1234',
    ]);

    return [$user, $company];
}

test('emite una factura electrónica de punta a punta', function () {
    (new CatalogSeeder)->run();
    [$user] = companyWithCredentials();

    Http::fake([
        '*idp*' => Http::response(['access_token' => 'fake-token', 'token_type' => 'bearer'], 200),
        '*recepcion*' => Http::response('', 202),
    ]);

    $token = $user->createToken('test', ['master'])->plainTextToken;

    $payload = [
        'tipo' => 'FE',
        'environment' => 'stag',
        'codigoSeguridad' => '12345678',
        'codigo_actividad_emisor' => '741203',
        'fecha_emision' => '2024-02-07T12:00:00-06:00',
        'condicion_venta' => '01',
        'medios_pago' => [['tipoMedioPago' => '01', 'totalMedioPago' => 11300.00]],
        'receptor_nombre' => 'Cliente ABC',
        'receptor_tipo_identif' => '01',
        'receptor_num_identif' => '108880777',
        'receptor_email' => 'cliente@example.com',
        'cod_moneda' => 'CRC', 'tipo_cambio' => '1.00',
        'total_serv_gravados' => '10000.00', 'total_gravados' => '10000.00',
        'total_ventas' => '10000.00', 'total_ventas_neta' => '10000.00',
        'total_impuestos' => '1300.00', 'total_comprobante' => '11300.00',
        'detalles' => [[
            'codigoCABYS' => '8399000000000', 'cantidad' => 1, 'unidadMedida' => 'Sp',
            'detalle' => 'Servicio', 'precioUnitario' => 10000.00, 'montoTotal' => 10000.00,
            'subTotal' => 10000.00, 'baseImponible' => 10000.00,
            'impuesto' => [['codigo' => '01', 'codigoTarifa' => '08', 'tarifa' => 13.00, 'monto' => 1300.00]],
            'impuestoAsumidoEmisorFabrica' => 0.00,
            'impuestoNeto' => 1300.00, 'montoTotalLinea' => 11300.00,
        ]],
    ];

    $response = $this->withToken($token)->postJson('/api/v1/documents', $payload);

    $response->assertStatus(201)
        ->assertJsonStructure(['clave', 'consecutivo', 'xml', 'xmlFirmado', 'envio', 'document_id']);

    // Clave de 50 dígitos y comprobante persistido.
    expect(strlen($response->json('clave')))->toBe(50);
    $this->assertDatabaseHas('documents', [
        'clave' => $response->json('clave'),
        'tipo_documento' => 'FE',
        'estado' => 'enviado',
    ]);

    // La firma es un XML válido con Signature XAdES.
    $signed = base64_decode($response->json('xmlFirmado'));
    expect($signed)->toContain('<ds:Signature')->toContain('SignaturePolicyIdentifier');

    // Se llamó al IDP y a recepción de Hacienda.
    Http::assertSent(fn ($r) => str_contains($r->url(), 'idp'));
    Http::assertSent(fn ($r) => str_contains($r->url(), 'recepcion'));
});

test('emitir sin certificado devuelve 422 con mensaje claro', function () {
    (new CatalogSeeder)->run();

    $user = User::create([
        'full_name' => 'Owner', 'user_name' => 'owner2', 'email' => 'o2@example.com',
        'about' => '', 'country' => 'crc', 'status' => '1', 'legacy_timestamp' => time(),
        'last_access' => time(), 'password' => password_hash('secret123', PASSWORD_BCRYPT, ['cost' => 4]),
        'avatar' => '0', 'settings' => null,
    ]);
    Company::create([
        'id' => $user->id, 'owner_user_id' => $user->id, 'nombre' => 'SinCert',
        'tipo_cedula' => '02', 'cedula' => '3109999999', 'env' => 'api-stag',
    ]);

    $token = $user->createToken('test', ['master'])->plainTextToken;

    $this->withToken($token)->postJson('/api/v1/documents', [
        'tipo' => 'FE', 'environment' => 'stag', 'condicion_venta' => '01',
        'detalles' => [], 'total_ventas' => '0', 'total_ventas_neta' => '0', 'total_comprobante' => '0',
    ])->assertStatus(422)->assertJsonStructure(['message']);
});
