#!/usr/bin/env php
<?php
/**
 * Captura golden masters del API legacy para los tests de paridad de la
 * migración a Laravel 12.
 *
 * Uso:
 *   php capture.php [baseUrl] [outDir]
 *
 * Por defecto apunta a http://localhost:8080/api.php (docker compose de
 * /legacy) y escribe fixtures JSON en <repo>/tests/Fixtures/golden/.
 *
 * Cada fixture guarda el request completo y la respuesta HTTP completa
 * (status, content-type, body). El campo "compare" indica cómo debe
 * compararse en los tests de paridad:
 *   - exact:      status + body byte a byte
 *   - json-exact: status + JSON semánticamente idéntico
 *   - signature:  status + shape; el XML firmado se verifica estructural y
 *                 criptográficamente (la firma incluye timestamp/aleatorio)
 *   - shape:      status + claves del JSON (valores no deterministas:
 *                 sessionKey, downloadCode, fechas)
 *   - status-only: solo el código HTTP
 */

$baseUrl = $argv[1] ?? 'http://localhost:8080/api.php';
$outDir  = $argv[2] ?? dirname(__DIR__, 2) . '/tests/Fixtures/golden';

if (!is_dir($outDir) && !mkdir($outDir, 0775, true)) {
    fwrite(STDERR, "No se pudo crear $outDir\n");
    exit(1);
}

require __DIR__.'/capture-lib.php';

/* ----------------------------------------------------------------------
 * Payloads base (v4.4) — tomados del test PHPUnit del legacy y de la
 * colección Postman, con fechas fijas para determinismo.
 * -------------------------------------------------------------------- */

$detallesFull = json_encode([
    [
        'codigoCABYS' => '0111100000100',
        'codigoComercial' => [
            ['tipo' => '01', 'codigo' => 'A123'],
            ['tipo' => '02', 'codigo' => 'B456'],
        ],
        'cantidad' => 2,
        'unidadMedida' => 'Unid',
        'tipoTransaccion' => '01',
        'unidadMedidaComercial' => 'Caja',
        'detalle' => 'Medicamento genérico',
        'numeroVINoSerie' => 'VIN123456789',
        'registroMedicamento' => 'REG-CR-2024-0001',
        'formaFarmaceutica' => 'TAB',
        'detalleSurtido' => [
            [
                'codigoCABYSSurtido' => '2399999002200',
                'codigoComercialSurtido' => [
                    ['tipoSurtido' => '01', 'codigoSurtido' => 'S123'],
                ],
                'cantidadSurtido' => 1,
                'unidadMedidaSurtido' => 'Unid',
                'unidadMedidaComercialSurtido' => 'Blister',
                'detalleSurtido' => 'Surtido de medicamento',
                'precioUnitarioSurtido' => 120.00,
                'montoTotalSurtido' => 120.00,
                'descuentoSurtido' => [
                    [
                        'montoDescuentoSurtido' => 10.00,
                        'codigoDescuentoSurtido' => '01',
                        'descuentoSurtidoOtros' => 'Descuento especial',
                    ],
                ],
                'subTotalSurtido' => 110.00,
                'ivaCobradoFabricaSurtido' => '01',
                'baseImponibleSurtido' => 105.00,
                'impuestoSurtido' => [
                    [
                        'codigoImpuestoSurtido' => '01',
                        'codigoTarifaIVASurtido' => '08',
                        'tarifaSurtido' => 13.00,
                        'montoImpuestoSurtido' => 13.65,
                        'datosImpuestoEspecificoSurtido' => [
                            'cantidadUnidadMedidaSurtido' => 1,
                            'porcentajeSurtido' => 5.0,
                            'proporcionSurtido' => 0.5,
                            'volumenUnidadConsumoSurtido' => 0.1,
                            'impuestoUnidadSurtido' => 2.00,
                        ],
                    ],
                ],
            ],
        ],
        'precioUnitario' => 150.00,
        'montoTotal' => 300.00,
        'descuento' => [
            [
                'montoDescuento' => 20.00,
                'codigoDescuento' => '99',
                'codigoDescuentoOTRO' => 'DESC-OTRO-001',
                'naturalezaDescuento' => 'Descuento por promoción',
            ],
        ],
        'subTotal' => 280.00,
        'IVACobradoFabrica' => '01',
        'baseImponible' => 270.00,
        'impuesto' => [
            [
                'codigo' => '01',
                'codigoTarifa' => '08',
                'tarifa' => 13.00,
                'factorIVA' => 1.0,
                'monto' => 35.10,
                'exoneracion' => [
                    'tipoDocumento' => '01',
                    'tipoDocumentoOtro' => 'OTRODOC',
                    'numeroDocumento' => 'EXON-2024-001',
                    'numeroArticulo' => '123501',
                    'numeroInciso' => '000010',
                    'nombreInstitucion' => '01',
                    'nombreInstitucionOtros' => 'Otra Institución',
                    'fechaEmision' => '2024-06-01T00:00:00',
                    'tarifaExoneracion' => 50.0,
                    'montoExoneracion' => 17.55,
                ],
            ],
            [
                'codigo' => '03',
                'codigoTarifa' => '01',
                'tarifa' => 2.00,
                'factorIVA' => 0.5,
                'monto' => 5.00,
                'datosImpuestoEspecifico' => [
                    'cantidadUnidadMedida' => 2,
                    'porcentaje' => 10.0,
                    'proporcion' => 0.2,
                    'volumenUnidadConsumo' => 0.5,
                    'impuestoUnidad' => 1.00,
                ],
            ],
        ],
        'impuestoAsumidoEmisorFabrica' => 2.00,
        'impuestoNeto' => 22.55,
        'montoTotalLinea' => 302.55,
    ],
    [
        'codigoCABYS' => '3110100000100',
        'cantidad' => 1,
        'unidadMedida' => 'Kg',
        'detalle' => 'Producto sin surtido ni descuentos',
        'precioUnitario' => 50.00,
        'montoTotal' => 50.00,
        'subTotal' => 50.00,
        'baseImponible' => 50.00,
        'impuesto' => [
            ['codigo' => '01', 'codigoTarifa' => '08', 'tarifa' => 13.00, 'monto' => 6.50],
        ],
        'impuestoAsumidoEmisorFabrica' => 0.00,
        'impuestoNeto' => 6.50,
        'montoTotalLinea' => 56.50,
    ],
]);

$detallesSimple = json_encode([
    [
        'codigoCABYS' => '8399000000000',
        'cantidad' => 1,
        'unidadMedida' => 'Sp',
        'detalle' => 'Servicio profesional',
        'precioUnitario' => 10000.00,
        'montoTotal' => 10000.00,
        'subTotal' => 10000.00,
        'baseImponible' => 10000.00,
        'impuesto' => [
            ['codigo' => '01', 'codigoTarifa' => '08', 'tarifa' => 13.00, 'monto' => 1300.00],
        ],
        'impuestoNeto' => 1300.00,
        'montoTotalLinea' => 11300.00,
    ],
]);

$mediosPago = json_encode([
    ['tipoMedioPago' => '01', 'totalMedioPago' => 1000.50],
    ['tipoMedioPago' => '02', 'totalMedioPago' => 500.00],
    ['tipoMedioPago' => '99', 'medioPagoOtros' => 'Custom Payment', 'totalMedioPago' => 250.75],
]);
$medioPagoSimple = json_encode([['tipoMedioPago' => '01', 'totalMedioPago' => 11300.00]]);

$otrosCargos = json_encode([
    ['tipoDocumentoOC' => '01', 'numeroDocumento' => 'DOC-123', 'detalle' => 'Cargo por servicio adicional', 'montoCargo' => 150.00],
    ['tipoDocumentoOC' => '02', 'numeroDocumento' => 'DOC-456', 'detalle' => 'Cargo por gestión', 'montoCargo' => 75.50],
]);

$referencia = json_encode([
    [
        'tipoDoc' => '99', 'tipoDocOtro' => 'Factura',
        'numero' => '50620032400020536006000100001010000000017100000017',
        'fechaEmision' => '2023-10-01T12:00:00',
        'codigo' => '99', 'codigoOtro' => 'OTRO1', 'razon' => 'Corrección de datos',
    ],
    [
        'tipoDoc' => '02',
        'numero' => '50620032400020536006000100001010000000017200000018',
        'fechaEmision' => '2023-10-02T15:30:00',
        'codigo' => '01', 'razon' => 'Devolucion de producto',
    ],
]);

$otros = json_encode([
    'otroTexto' => ['codigo' => 'COD1', 'texto' => 'Texto opcional 1'],
    'otroContenido' => [
        ['codigo' => 'CONT1', 'contenidoEstructurado' => ['ContactoDesarrollador' => ['Correo' => 'developer@example.com', 'Nombre' => 'Developer Name', 'Telefono' => '+123456789']]],
        ['codigo' => 'CONT2', 'contenidoEstructurado' => ['SoporteTecnico' => ['Correo' => 'support@example.com', 'Nombre' => 'Support Team', 'Telefono' => '+987654321']]],
    ],
]);

$emisor = [
    'proveedor_sistemas' => 'Proveedor XYZ',
    'codigo_actividad_emisor' => '401002',
    'emisor_nombre' => 'Empresa XYZ',
    'emisor_tipo_identif' => '01',
    'emisor_num_identif' => '3101234567',
    'emisor_nombre_comercial' => 'Comercial XYZ',
    'emisor_provincia' => '3',
    'emisor_canton' => '01',
    'emisor_distrito' => '01',
    'emisor_barrio' => 'Barrio01',
    'emisor_otras_senas' => 'Dirección de prueba',
    'emisor_cod_pais_tel' => '506',
    'emisor_tel' => '22223333',
    'emisor_email' => 'empresa@example.com',
];

$receptor = [
    'receptor_nombre' => 'Cliente ABC',
    'receptor_tipo_identif' => '02',
    'receptor_num_identif' => '206540123',
    'receptor_nombre_comercial' => 'Comercial ABC',
    'receptor_provincia' => '4',
    'receptor_canton' => '02',
    'receptor_distrito' => '03',
    'receptor_barrio' => 'Barrio02',
    'receptor_otras_senas' => 'Calle 123, Edificio ABC',
    'receptor_cod_pais_tel' => '506',
    'receptor_tel' => '88887777',
    'receptor_email' => 'cliente@example.com',
    'codigo_actividad_receptor' => '502101',
];

$totalesFull = [
    'condicion_venta' => '01',
    'condicion_venta_otros' => 'Venta especial',
    'plazo_credito' => '30',
    'cod_moneda' => 'CRC',
    'tipo_cambio' => '1.00',
    'total_serv_gravados' => '0.00',
    'total_serv_exentos' => '200000.00',
    'total_serv_exonerados' => '0.00',
    'total_serv_no_sujeto' => '0.00',
    'total_merc_gravada' => '0.00',
    'total_merc_exenta' => '0.00',
    'total_merc_exonerada' => '0.00',
    'total_merc_no_sujeta' => '0.00',
    'total_gravados' => '0.00',
    'total_exento' => '200000.00',
    'total_exonerado' => '0.00',
    'total_no_sujeto' => '0.00',
    'total_ventas' => '1000.00',
    'total_descuentos' => '100.00',
    'total_ventas_neta' => '1000.00',
    'totalDesgloseImpuesto' => '0.00',
    'total_impuestos' => '0.00',
    'total_impuestos_asumidos_fabrica' => '0.00',
    'totalIVADevuelto' => '0.00',
    'totalOtrosCargos' => '225.50',
    'total_comprobante' => '1000.00',
    'registrofiscal8707' => 'REG-8707-001',
    'receptor_otras_senas_extranjero' => '123 Main St, Miami, FL, USA',
];

$totalesSimple = [
    'condicion_venta' => '01',
    'plazo_credito' => '0',
    'cod_moneda' => 'CRC',
    'tipo_cambio' => '1.00',
    'total_serv_gravados' => '10000.00',
    'total_gravados' => '10000.00',
    'total_ventas' => '10000.00',
    'total_descuentos' => '0.00',
    'total_ventas_neta' => '10000.00',
    'total_impuestos' => '1300.00',
    'total_comprobante' => '11300.00',
];

$feFull = array_merge($emisor, $receptor, $totalesFull, [
    'clave' => '50620032400310123456700100001010000000017100000017',
    'consecutivo' => '00100001010000000017',
    'fecha_emision' => '2024-02-07T12:00:00',
    'medios_pago' => $mediosPago,
    'detalles' => $detallesFull,
    'otrosCargos' => $otrosCargos,
    'informacion_referencia' => $referencia,
    'otros' => $otros,
]);

$feMinimal = array_merge($emisor, $receptor, $totalesSimple, [
    'clave' => '50620032400310123456700100001010000000018100000018',
    'consecutivo' => '00100001010000000018',
    'fecha_emision' => '2024-02-07T12:00:00',
    'medios_pago' => $medioPagoSimple,
    'detalles' => $detallesSimple,
]);

/* ----------------------------------------------------------------------
 * Corpus de casos
 * -------------------------------------------------------------------- */

$suffix = ['w' => null, 'r' => null]; // helper visual

$cases = [
    // --- Tubería / contrato básico ---
    ['name' => 'version', 'compare' => 'exact',
     'params' => ['w' => 'version', 'r' => 'version']],
    ['name' => 'version-get', 'compare' => 'exact', 'method' => 'GET',
     'params' => ['w' => 'version', 'r' => 'version']],
    ['name' => 'unknown-module', 'compare' => 'exact',
     'params' => ['w' => 'noexiste', 'r' => 'nada']],
    ['name' => 'unknown-route', 'compare' => 'exact',
     'params' => ['w' => 'clave', 'r' => 'noexiste']],
    ['name' => 'no-params-at-all', 'compare' => 'exact', 'method' => 'GET', 'params' => []],
    ['name' => 'invalid-json-body', 'compare' => 'exact', 'bodyMode' => 'rawjson',
     'rawBody' => '{esto no es json valido'],
    ['name' => 'ejemplo-hola', 'compare' => 'exact',
     'params' => ['w' => 'ejemplo', 'r' => 'module_hola']],

    // --- clave ---
    ['name' => 'clave-fe-fisico', 'compare' => 'exact',
     'params' => ['w' => 'clave', 'r' => 'clave', 'tipoDocumento' => 'FE', 'tipoCedula' => 'fisico',
                  'cedula' => '702320717', 'codigoPais' => '506', 'consecutivo' => '0000001030',
                  'situacion' => 'normal', 'codigoSeguridad' => '00000030']],
    ['name' => 'clave-te-juridico', 'compare' => 'exact',
     'params' => ['w' => 'clave', 'r' => 'clave', 'tipoDocumento' => 'TE', 'tipoCedula' => 'juridico',
                  'cedula' => '3101234567', 'codigoPais' => '506', 'consecutivo' => '0000000005',
                  'situacion' => 'normal', 'codigoSeguridad' => '12345678',
                  'sucursal' => '002', 'terminal' => '00003']],
    ['name' => 'clave-nc-contingencia', 'compare' => 'exact',
     'params' => ['w' => 'clave', 'r' => 'clave', 'tipoDocumento' => 'NC', 'tipoCedula' => 'fisico',
                  'cedula' => '702320717', 'consecutivo' => '0000000007',
                  'situacion' => 'contingencia', 'codigoSeguridad' => '99999999']],
    ['name' => 'clave-nd-sininternet', 'compare' => 'exact',
     'params' => ['w' => 'clave', 'r' => 'clave', 'tipoDocumento' => 'ND', 'tipoCedula' => 'fisico',
                  'cedula' => '702320717', 'consecutivo' => '0000000008',
                  'situacion' => 'sininternet', 'codigoSeguridad' => '11111111']],
    ['name' => 'clave-missing-param', 'compare' => 'exact',
     'params' => ['w' => 'clave', 'r' => 'clave', 'tipoCedula' => 'fisico',
                  'cedula' => '702320717', 'consecutivo' => '0000001030',
                  'situacion' => 'normal', 'codigoSeguridad' => '00000030']],

    // --- genXML ---
    ['name' => 'genxml-test', 'compare' => 'exact',
     'params' => ['w' => 'genXML', 'r' => 'test']],
    ['name' => 'genxml-fe-full', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_fe'], $feFull)],
    ['name' => 'genxml-fe-minimal', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_fe'], $feMinimal)],
    ['name' => 'genxml-fe-json-body', 'compare' => 'exact', 'bodyMode' => 'rawjson',
     'rawBody' => json_encode(array_merge(['w' => 'genXML', 'r' => 'gen_xml_fe'], $feMinimal))],
    ['name' => 'genxml-fe-missing-clave', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_fe'],
                             array_diff_key($feFull, ['clave' => 1]))],
    ['name' => 'genxml-nc-full', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_nc'], $feFull,
                             ['clave' => '50620032400310123456700100001030000000019100000019',
                              'consecutivo' => '00100001030000000019', 'otrosType' => ''])],
    ['name' => 'genxml-nd-full', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_nd'], $feFull,
                             ['clave' => '50620032400310123456700100001020000000020100000020',
                              'consecutivo' => '00100001020000000020', 'otrosType' => ''])],
    ['name' => 'genxml-te-omitir-receptor', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_te'], $emisor, $totalesSimple,
                             ['clave' => '50620032400310123456700100001040000000021100000021',
                              'consecutivo' => '00100001040000000021',
                              'fecha_emision' => '2024-02-07T12:00:00',
                              'medios_pago' => $medioPagoSimple, 'detalles' => $detallesSimple,
                              'omitir_receptor' => 'true'])],
    ['name' => 'genxml-te-con-receptor', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_te'], $emisor, $receptor, $totalesSimple,
                             ['clave' => '50620032400310123456700100001040000000022100000022',
                              'consecutivo' => '00100001040000000022',
                              'fecha_emision' => '2024-02-07T12:00:00',
                              'medios_pago' => $medioPagoSimple, 'detalles' => $detallesSimple])],
    ['name' => 'genxml-fec', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_fec'], $emisor, $receptor, $totalesSimple,
                             ['clave' => '50620032400310123456700100001080000000023100000023',
                              'consecutivo' => '00100001080000000023',
                              'fecha_emision' => '2024-02-07T12:00:00',
                              'medios_pago' => $medioPagoSimple, 'detalles' => $detallesSimple,
                              'informacion_referencia' => $referencia])],
    ['name' => 'genxml-fee', 'compare' => 'exact',
     'params' => array_merge(['w' => 'genXML', 'r' => 'gen_xml_fee'], $emisor, $totalesSimple,
                             ['clave' => '50620032400310123456700100001090000000024100000024',
                              'consecutivo' => '00100001090000000024',
                              'fecha_emision' => '2024-02-07T12:00:00',
                              'receptor_nombre' => 'Foreign Buyer LLC',
                              'receptor_tipo_identif' => '05',
                              'receptor_num_identif' => 'EXT-0001',
                              'receptor_otras_senas_extranjero' => '123 Main St, Miami, FL, USA',
                              'receptor_email' => 'buyer@example.com',
                              'medios_pago' => $medioPagoSimple, 'detalles' => $detallesSimple])],
    ['name' => 'genxml-mr-aceptado', 'compare' => 'exact',
     'params' => ['w' => 'genXML', 'r' => 'gen_xml_mr',
                  'clave' => '50620032400310123456700100001010000000017100000017',
                  'codigo_actividad' => '401002',
                  'numero_cedula_emisor' => '3101234567',
                  'fecha_emision_doc' => '2024-02-07T12:00:00-06:00',
                  'mensaje' => '1', 'detalle_mensaje' => 'Acepto por completo',
                  'monto_total_impuesto' => '0.00', 'total_factura' => '1000.00',
                  'numero_cedula_receptor' => '206540123',
                  'numero_consecutivo_receptor' => '00100001050000000001']],
    ['name' => 'genxml-mr-rechazado', 'compare' => 'exact',
     'params' => ['w' => 'genXML', 'r' => 'gen_xml_mr',
                  'clave' => '50620032400310123456700100001010000000017100000017',
                  'codigo_actividad' => '401002',
                  'numero_cedula_emisor' => '3101234567',
                  'fecha_emision_doc' => '2024-02-07T12:00:00-06:00',
                  'mensaje' => '3', 'detalle_mensaje' => 'Rechazado por errores',
                  'monto_total_impuesto' => '130.00', 'total_factura' => '1130.00',
                  'numero_cedula_receptor' => '206540123',
                  'numero_consecutivo_receptor' => '00100001050000000002']],
    ['name' => 'genxml-mr-missing-param', 'compare' => 'exact',
     'params' => ['w' => 'genXML', 'r' => 'gen_xml_mr', 'clave' => '506200324003101234567001']],

    // --- makeJson / XmlToBase64 / makeQR / check ---
    ['name' => 'makejson', 'compare' => 'exact',
     'params' => ['w' => 'makeJson', 'r' => 'makeJson',
                  'clave' => '50620032400310123456700100001010000000017100000017',
                  'fecha' => '2024-02-07T12:00:00-06:00',
                  'emi_tipoIdentificacion' => '01', 'emi_numeroIdentificacion' => '3101234567',
                  'recp_tipoIdentificacion' => '02', 'recp_numeroIdentificacion' => '206540123',
                  'comprobanteXml' => base64_encode('<xml>demo</xml>')]],
    ['name' => 'makejson-missing-param', 'compare' => 'exact',
     'params' => ['w' => 'makeJson', 'r' => 'makeJson', 'clave' => 'x']],
    ['name' => 'xmltobase64-bad-code', 'compare' => 'exact',
     'params' => ['w' => 'XmlToBase64', 'r' => 'encode', 'downloadCode' => 'cafedeadbeef00000000000000000000']],
    ['name' => 'makeqr', 'compare' => 'exact',
     'params' => ['w' => 'makeQR', 'r' => 'makeQR',
                  'string' => '50620032400310123456700100001010000000017100000017']],
    ['name' => 'check-xml-fe', 'compare' => 'exact',
     'params' => ['w' => 'check', 'r' => 'checkxml', 'tipoDocumento' => 'FE']],

    // --- token / send / consultar (solo rutas de error; sin credenciales reales) ---
    ['name' => 'token-missing-params', 'compare' => 'exact',
     'params' => ['w' => 'token', 'r' => 'gettoken', 'grant_type' => 'password']],
    ['name' => 'send-missing-params', 'compare' => 'exact',
     'params' => ['w' => 'send', 'r' => 'json', 'token' => 'fake']],
    ['name' => 'consultar-missing-params', 'compare' => 'exact',
     'params' => ['w' => 'consultar', 'r' => 'consultarCom', 'clave' => '123']],
    ['name' => 'crlibreall-fe-stub', 'compare' => 'exact',
     'params' => ['w' => 'crlibreall', 'r' => 'FE']],

    // --- crypto ---
    ['name' => 'crypto-makekey', 'compare' => 'shape',
     'notes' => 'La clave generada es aleatoria; comparar solo shape {status, resp}.',
     'params' => ['w' => 'crypto', 'r' => 'makeKey']],
    ['name' => 'crypto-encrypt-denied', 'compare' => 'exact',
     'notes' => 'Ruta con users_noAccess: debe negar acceso.',
     'params' => ['w' => 'crypto', 'r' => 'encrypt', 'texto' => 'hola']],
];

/* ----------------------------------------------------------------------
 * Ejecución: casos stateless + cadena users/upload/firmar
 * -------------------------------------------------------------------- */

echo "Capturando contra $baseUrl\n";
echo "Fixtures en $outDir\n\n";

$seq = 1;
$responses = [];
foreach ($cases as $case) {
    $resp = callApi($baseUrl, $case);
    saveFixture($outDir, $seq++, $case, $resp);
    $responses[$case['name']] = $resp;
}

/* --- Cadena con estado: register -> login -> upload cert -> firmar --- */

$user = 'goldenuser';
$pwd  = 'Golden123*';

$chain = [];

$chain[] = ['name' => 'users-register', 'compare' => 'shape',
    'notes' => 'idUser depende del estado de la BD.',
    'params' => ['w' => 'users', 'r' => 'users_register', 'fullName' => 'Golden User',
                 'userName' => $user, 'email' => 'golden@example.com', 'about' => '{}',
                 'country' => 'crc', 'pwd' => $pwd]];
$chain[] = ['name' => 'users-register-duplicate', 'compare' => 'exact',
    'params' => ['w' => 'users', 'r' => 'users_register', 'fullName' => 'Golden User',
                 'userName' => $user, 'email' => 'golden@example.com', 'about' => '{}',
                 'country' => 'crc', 'pwd' => $pwd]];
$chain[] = ['name' => 'users-login-wrong-pwd', 'compare' => 'exact',
    'params' => ['w' => 'users', 'r' => 'users_log_me_in', 'userName' => $user, 'pwd' => 'incorrecta']];
$chain[] = ['name' => 'users-login-ok', 'compare' => 'shape',
    'notes' => 'sessionKey es aleatoria; comparar shape.',
    'params' => ['w' => 'users', 'r' => 'users_log_me_in', 'userName' => $user, 'pwd' => $pwd]];
$chain[] = ['name' => 'users-login-email', 'compare' => 'shape',
    'notes' => 'Login por email; sessionKey aleatoria.',
    'params' => ['w' => 'users', 'r' => 'users_log_me_in', 'userName' => 'golden@example.com', 'pwd' => $pwd]];

foreach ($chain as $case) {
    $resp = callApi($baseUrl, $case);
    saveFixture($outDir, $seq++, $case, $resp);
    $responses[$case['name']] = $resp;
}

# Cada login destruye las sesiones previas del usuario: usar la del último.
$sessionKey = extractKey($responses['users-login-email']['body'] ?? '', 'sessionKey')
    ?: extractKey($responses['users-login-ok']['body'] ?? '', 'sessionKey');
echo "\nsessionKey: " . ($sessionKey ? substr($sessionKey, 0, 25) . '...' : 'NO OBTENIDA') . "\n\n";

$auth = ['iam' => $user, 'sessionKey' => (string) $sessionKey];

$chain2 = [
    ['name' => 'users-get-my-details', 'compare' => 'shape',
     'params' => array_merge(['w' => 'users', 'r' => 'users_get_my_details'], $auth)],
    ['name' => 'users-confirm-session', 'compare' => 'shape',
     'params' => array_merge(['w' => 'users', 'r' => 'users_confirm_session_vilidity'], $auth)],
    ['name' => 'users-bad-session', 'compare' => 'exact',
     'notes' => 'Debe dar HTTP 440 sesión no válida.',
     'params' => ['w' => 'users', 'r' => 'users_get_my_details', 'iam' => $user,
                  'sessionKey' => 'sessionfalsa123']],
    ['name' => 'upload-cert', 'compare' => 'shape',
     'notes' => 'downloadCode es md5 aleatorio; comparar shape.',
     'params' => array_merge(['w' => 'fileUploader', 'r' => 'subir_certif'], $auth),
     'files' => ['fileToUpload' => __DIR__ . '/test-cert.p12']],
];

foreach ($chain2 as $case) {
    $resp = callApi($baseUrl, $case);
    saveFixture($outDir, $seq++, $case, $resp);
    $responses[$case['name']] = $resp;
}

$downloadCode = extractKey($responses['upload-cert']['body'] ?? '', 'downloadCode');
echo "\ndownloadCode: " . ($downloadCode ?: 'NO OBTENIDO') . "\n\n";

// XML a firmar: el de genxml-fe-full
$xmlB64 = extractKey($responses['genxml-fe-full']['body'] ?? '', 'xml');

$chain3 = [
    ['name' => 'xmltobase64-good-code', 'compare' => 'shape',
     'params' => ['w' => 'XmlToBase64', 'r' => 'encode', 'downloadCode' => (string) $downloadCode]],
    ['name' => 'firmar-fe', 'compare' => 'signature',
     'notes' => 'Firma XAdES: incluye SigningTime y aleatorio. Verificar estructural/criptográficamente.',
     'params' => ['w' => 'firmarXML', 'r' => 'firmar', 'p12Url' => (string) $downloadCode,
                  'pinP12' => '1234', 'inXml' => (string) $xmlB64]],
    ['name' => 'firmar-bad-pin', 'compare' => 'exact',
     'params' => ['w' => 'firmarXML', 'r' => 'firmar', 'p12Url' => (string) $downloadCode,
                  'pinP12' => '9999', 'inXml' => (string) $xmlB64]],
    ['name' => 'firmar-missing-p12', 'compare' => 'exact',
     'params' => ['w' => 'firmarXML', 'r' => 'firmar', 'pinP12' => '1234', 'inXml' => 'PHhtbC8+']],
    ['name' => 'users-logout', 'compare' => 'shape',
     'params' => array_merge(['w' => 'users', 'r' => 'users_log_me_out'], $auth)],
];

foreach ($chain3 as $case) {
    $resp = callApi($baseUrl, $case);
    saveFixture($outDir, $seq++, $case, $resp);
    $responses[$case['name']] = $resp;
}

echo "\nListo: " . ($seq - 1) . " fixtures capturados en $outDir\n";
