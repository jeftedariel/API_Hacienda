<?php

/**
 * Tablas de rutas del módulo genXML, extraídas mecánicamente de
 * legacy/api/contrib/genXML/module.php (genXML_init()). No editar a mano:
 * son contrato de paridad con el API legacy.
 */
return array (
  'gen_xml_mr' => 
  array (
    'action' => 'genXMLMr',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'codigo_actividad',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'numero_cedula_emisor',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'fecha_emision_doc',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'mensaje',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'detalle_mensaje',
        'def' => '',
        'req' => false,
      ),
      6 => 
      array (
        'key' => 'monto_total_impuesto',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'total_factura',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'numero_cedula_receptor',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'numero_consecutivo_receptor',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  'gen_xml_fe' => 
  array (
    'action' => 'genXMLFe',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'codigo_actividad_receptor',
        'def' => '',
        'req' => false,
      ),
      4 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      10 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      13 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      14 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      16 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      17 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      18 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => true,
      ),
      19 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      20 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => true,
      ),
      21 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'receptor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      27 => 
      array (
        'key' => 'receptor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      33 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      34 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => true,
      ),
      36 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      37 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      39 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'total_serv_exonerados',
        'def' => '',
        'req' => false,
      ),
      41 => 
      array (
        'key' => 'total_serv_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      43 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'total_merc_exonerada',
        'def' => '',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'total_merc_no_sujeta',
        'def' => '',
        'req' => false,
      ),
      46 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      47 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      48 => 
      array (
        'key' => 'total_exonerado',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'total_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      50 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      51 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      52 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      53 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      54 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      55 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      57 => 
      array (
        'key' => 'totalIVADevuelto',
        'def' => '0',
        'req' => false,
      ),
      58 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      59 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      60 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
      61 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      62 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => false,
      ),
      63 => 
      array (
        'key' => 'otrosCargos',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  'gen_xml_nc' => 
  array (
    'action' => 'genXMLNC',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'codigo_actividad_receptor',
        'def' => '',
        'req' => false,
      ),
      4 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      10 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      13 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      14 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      16 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      17 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      18 => 
      array (
        'key' => 'omitir_receptor',
        'def' => 'false',
        'req' => false,
      ),
      19 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => false,
      ),
      20 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => false,
      ),
      21 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      27 => 
      array (
        'key' => 'receptor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'receptor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      33 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      34 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      36 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => true,
      ),
      37 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      39 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      41 => 
      array (
        'key' => 'total_serv_exonerados',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'total_serv_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      43 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'total_merc_exonerada',
        'def' => '',
        'req' => false,
      ),
      46 => 
      array (
        'key' => 'total_merc_no_sujeta',
        'def' => '',
        'req' => false,
      ),
      47 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      48 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'total_exonerado',
        'def' => '',
        'req' => false,
      ),
      50 => 
      array (
        'key' => 'total_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      51 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      52 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      53 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      54 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      55 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      57 => 
      array (
        'key' => 'totalIVADevuelto',
        'def' => '0',
        'req' => false,
      ),
      58 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      59 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      60 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
      61 => 
      array (
        'key' => 'otrosType',
        'def' => '',
        'req' => false,
      ),
      62 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      63 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => true,
      ),
      64 => 
      array (
        'key' => 'otrosCargos',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  'gen_xml_nd' => 
  array (
    'action' => 'genXMLND',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'codigo_actividad_receptor',
        'def' => '',
        'req' => false,
      ),
      4 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      10 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      13 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      14 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      16 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      17 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      18 => 
      array (
        'key' => 'omitir_receptor',
        'def' => 'false',
        'req' => false,
      ),
      19 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => false,
      ),
      20 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => false,
      ),
      21 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      27 => 
      array (
        'key' => 'receptor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'receptor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      33 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      34 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      36 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => true,
      ),
      37 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      39 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      41 => 
      array (
        'key' => 'total_serv_exonerados',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'total_serv_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      43 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'total_merc_exonerada',
        'def' => '',
        'req' => false,
      ),
      46 => 
      array (
        'key' => 'total_merc_no_sujeta',
        'def' => '',
        'req' => false,
      ),
      47 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      48 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'total_exonerado',
        'def' => '',
        'req' => false,
      ),
      50 => 
      array (
        'key' => 'total_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      51 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      52 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      53 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      54 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      55 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      57 => 
      array (
        'key' => 'totalIVADevuelto',
        'def' => '0',
        'req' => false,
      ),
      58 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      59 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      60 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
      61 => 
      array (
        'key' => 'otrosType',
        'def' => '',
        'req' => false,
      ),
      62 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      63 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => true,
      ),
      64 => 
      array (
        'key' => 'otrosCargos',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  'gen_xml_te' => 
  array (
    'action' => 'genXMLTE',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      9 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      10 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      13 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      14 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      15 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      16 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      17 => 
      array (
        'key' => 'omitir_receptor',
        'def' => 'false',
        'req' => false,
      ),
      18 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => false,
      ),
      19 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => false,
      ),
      20 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => false,
      ),
      21 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'receptor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      27 => 
      array (
        'key' => 'receptor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      33 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      34 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => true,
      ),
      36 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      37 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      39 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'total_serv_exonerados',
        'def' => '',
        'req' => false,
      ),
      41 => 
      array (
        'key' => 'total_serv_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      43 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'total_merc_exonerada',
        'def' => '',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'total_merc_no_sujeta',
        'def' => '',
        'req' => false,
      ),
      46 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      47 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      48 => 
      array (
        'key' => 'total_exonerado',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'total_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      50 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      51 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      52 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      53 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      54 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      55 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'totalIVADevuelto',
        'def' => '0',
        'req' => false,
      ),
      57 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      58 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      59 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
      60 => 
      array (
        'key' => 'otrosType',
        'def' => '',
        'req' => false,
      ),
      61 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      62 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => false,
      ),
      63 => 
      array (
        'key' => 'otrosCargos',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  'gen_xml_fec' => 
  array (
    'action' => 'genXMLFec',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => false,
      ),
      3 => 
      array (
        'key' => 'codigo_actividad_receptor',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      10 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      13 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      14 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'emisor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      16 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      17 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      18 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      19 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => true,
      ),
      20 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      21 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => true,
      ),
      22 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      27 => 
      array (
        'key' => 'receptor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      33 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      34 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => true,
      ),
      36 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      37 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      39 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'total_serv_exonerados',
        'def' => '',
        'req' => false,
      ),
      41 => 
      array (
        'key' => 'total_serv_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      43 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'total_merc_exonerada',
        'def' => '',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'total_merc_no_sujeta',
        'def' => '',
        'req' => false,
      ),
      46 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      47 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      48 => 
      array (
        'key' => 'total_exonerado',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'total_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      50 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      51 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      52 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      53 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      54 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      55 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      57 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      58 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
      59 => 
      array (
        'key' => 'otrosType',
        'def' => '',
        'req' => false,
      ),
      60 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      61 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => true,
      ),
      62 => 
      array (
        'key' => 'otrosCargos',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  'gen_xml_fee' => 
  array (
    'action' => 'genXMLFee',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      9 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      10 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      13 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      14 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      15 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      16 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      17 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => false,
      ),
      18 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      19 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => true,
      ),
      20 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      21 => 
      array (
        'key' => 'receptor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      27 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'plazo_credito',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      31 => 
      array (
        'key' => 'otrosCargos',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      33 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      34 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      36 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      37 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      38 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      39 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      41 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      43 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      46 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      47 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      48 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  'test' => 
  array (
    'action' => 'test',
    'params' => 
    array (
    ),
  ),
);
