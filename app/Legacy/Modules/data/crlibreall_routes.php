<?php

/**
 * Tablas de rutas del módulo crlibreall, extraídas mecánicamente de
 * legacy/api/contrib/crlibreall/module.php. Las acciones legacy eran stubs
 * vacíos (allFE solo cargaba el módulo clave; allNC/allND no hacían nada).
 */
return array (
  'FE' => 
  array (
    'action' => 'allFE',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'tipoDocumento',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'tipoCedula',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'cedula',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'codigoPais',
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
        'key' => 'situacion',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'terminal',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'sucursal',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'codigoSeguridad',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      10 => 
      array (
        'key' => 'proveedor_sistemas',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'codigo_actividad_emisor',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'codigo_actividad_receptor',
        'def' => '',
        'req' => false,
      ),
      13 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      14 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      16 => 
      array (
        'key' => 'emisor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      17 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      18 => 
      array (
        'key' => 'emisor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      19 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => true,
      ),
      20 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => true,
      ),
      21 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => true,
      ),
      22 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => true,
      ),
      24 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      27 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => true,
      ),
      28 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      29 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => true,
      ),
      30 => 
      array (
        'key' => 'receptor_identif_extranjero',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'receptor_nombre_comercial',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      33 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      34 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      36 => 
      array (
        'key' => 'receptor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      37 => 
      array (
        'key' => 'receptor_otras_senas_extranjero',
        'def' => '',
        'req' => false,
      ),
      38 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      39 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      41 => 
      array (
        'key' => 'registrofiscal8707',
        'def' => '',
        'req' => false,
      ),
      42 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      43 => 
      array (
        'key' => 'condicion_venta_otros',
        'def' => '',
        'req' => false,
      ),
      44 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      45 => 
      array (
        'key' => 'medios_pago',
        'def' => '',
        'req' => true,
      ),
      46 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      47 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      48 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => false,
      ),
      49 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => false,
      ),
      50 => 
      array (
        'key' => 'total_serv_exonerados',
        'def' => '',
        'req' => false,
      ),
      51 => 
      array (
        'key' => 'total_serv_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      52 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => false,
      ),
      53 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => false,
      ),
      54 => 
      array (
        'key' => 'total_merc_exonerada',
        'def' => '',
        'req' => false,
      ),
      55 => 
      array (
        'key' => 'total_merc_no_sujeta',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => false,
      ),
      57 => 
      array (
        'key' => 'total_exento',
        'def' => '',
        'req' => false,
      ),
      58 => 
      array (
        'key' => 'total_exonerado',
        'def' => '',
        'req' => false,
      ),
      59 => 
      array (
        'key' => 'total_no_sujeto',
        'def' => '',
        'req' => false,
      ),
      60 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      61 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => false,
      ),
      62 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      63 => 
      array (
        'key' => 'totalDesgloseImpuesto',
        'def' => '',
        'req' => false,
      ),
      64 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => false,
      ),
      65 => 
      array (
        'key' => 'total_impuestos_asumidos_fabrica',
        'def' => '',
        'req' => false,
      ),
      66 => 
      array (
        'key' => 'totalIVADevuelto',
        'def' => '0',
        'req' => false,
      ),
      67 => 
      array (
        'key' => 'totalOtrosCargos',
        'def' => '',
        'req' => false,
      ),
      68 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      69 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => false,
      ),
      70 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      71 => 
      array (
        'key' => 'informacion_referencia',
        'def' => '',
        'req' => true,
      ),
      72 => 
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
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'emisor_tipo_indetif',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'nombre_comercial',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => false,
      ),
      9 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => false,
      ),
      10 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      11 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      12 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      13 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      14 => 
      array (
        'key' => 'emisor_cod_pais_fax',
        'def' => '',
        'req' => false,
      ),
      15 => 
      array (
        'key' => 'emisor_fax',
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
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      26 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      27 => 
      array (
        'key' => 'receptor_cod_pais_fax',
        'def' => '',
        'req' => false,
      ),
      28 => 
      array (
        'key' => 'receptor_fax',
        'def' => '',
        'req' => false,
      ),
      29 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      31 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'medio_pago',
        'def' => '',
        'req' => true,
      ),
      33 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      34 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      35 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => true,
      ),
      36 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => true,
      ),
      37 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => true,
      ),
      39 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => true,
      ),
      40 => 
      array (
        'key' => 'total_exentos',
        'def' => '',
        'req' => true,
      ),
      41 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      42 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => true,
      ),
      43 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      44 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => true,
      ),
      45 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      46 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => true,
      ),
      47 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      48 => 
      array (
        'key' => 'infoRefeTipoDoc',
        'def' => '',
        'req' => true,
      ),
      49 => 
      array (
        'key' => 'infoRefeNumero',
        'def' => '',
        'req' => true,
      ),
      50 => 
      array (
        'key' => 'infoRefeFechaEmision',
        'def' => '',
        'req' => true,
      ),
      51 => 
      array (
        'key' => 'infoRefeCodigo',
        'def' => '',
        'req' => true,
      ),
      52 => 
      array (
        'key' => 'infoRefeRazon',
        'def' => '',
        'req' => true,
      ),
      53 => 
      array (
        'key' => 'p12Url',
        'def' => '',
        'req' => true,
      ),
      54 => 
      array (
        'key' => 'pinP12',
        'def' => '',
        'req' => true,
      ),
      55 => 
      array (
        'key' => 'inXml',
        'def' => '',
        'req' => false,
      ),
      56 => 
      array (
        'key' => 'tipodoc',
        'def' => '',
        'req' => true,
      ),
      57 => 
      array (
        'key' => 'grant_type',
        'def' => '',
        'req' => true,
      ),
      58 => 
      array (
        'key' => 'client_id',
        'def' => '',
        'req' => true,
      ),
      59 => 
      array (
        'key' => 'client_secret',
        'def' => '',
        'req' => false,
      ),
      60 => 
      array (
        'key' => 'username',
        'def' => '',
        'req' => true,
      ),
      61 => 
      array (
        'key' => 'password',
        'def' => '',
        'req' => true,
      ),
      62 => 
      array (
        'key' => 'token',
        'def' => '',
        'req' => true,
      ),
      63 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      64 => 
      array (
        'key' => 'fecha',
        'def' => '',
        'req' => true,
      ),
      65 => 
      array (
        'key' => 'emi_tipoIdentificacion',
        'def' => '',
        'req' => true,
      ),
      66 => 
      array (
        'key' => 'emi_numeroIdentificacion',
        'def' => '',
        'req' => false,
      ),
      67 => 
      array (
        'key' => 'recp_tipoIdentificacion',
        'def' => '',
        'req' => true,
      ),
      68 => 
      array (
        'key' => 'recp_numeroIdentificacion',
        'def' => '',
        'req' => true,
      ),
      69 => 
      array (
        'key' => 'comprobanteXml',
        'def' => '',
        'req' => true,
      ),
      70 => 
      array (
        'key' => 'client_id',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  'NC' => 
  array (
    'action' => 'allNC',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'tipoDocumento',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'tipoCedula',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'cedula',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'codigoPais',
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
        'key' => 'situacion',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'terminal',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'sucursal',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'codigoSeguridad',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      10 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      13 => 
      array (
        'key' => 'emisor_tipo_indetif',
        'def' => '',
        'req' => true,
      ),
      14 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'nombre_comercial',
        'def' => '',
        'req' => true,
      ),
      16 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => false,
      ),
      17 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => false,
      ),
      18 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => false,
      ),
      19 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      20 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      21 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'emisor_cod_pais_fax',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'emisor_fax',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      26 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => true,
      ),
      27 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      28 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => true,
      ),
      29 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      33 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      34 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'receptor_cod_pais_fax',
        'def' => '',
        'req' => false,
      ),
      36 => 
      array (
        'key' => 'receptor_fax',
        'def' => '',
        'req' => false,
      ),
      37 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      39 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'medio_pago',
        'def' => '',
        'req' => true,
      ),
      41 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      42 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      43 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => true,
      ),
      44 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => true,
      ),
      45 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => true,
      ),
      46 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => true,
      ),
      47 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => true,
      ),
      48 => 
      array (
        'key' => 'total_exentos',
        'def' => '',
        'req' => true,
      ),
      49 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      50 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => true,
      ),
      51 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      52 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => true,
      ),
      53 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      54 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => true,
      ),
      55 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      56 => 
      array (
        'key' => 'infoRefeTipoDoc',
        'def' => '',
        'req' => true,
      ),
      57 => 
      array (
        'key' => 'infoRefeNumero',
        'def' => '',
        'req' => true,
      ),
      58 => 
      array (
        'key' => 'infoRefeFechaEmision',
        'def' => '',
        'req' => true,
      ),
      59 => 
      array (
        'key' => 'infoRefeCodigo',
        'def' => '',
        'req' => true,
      ),
      60 => 
      array (
        'key' => 'infoRefeRazon',
        'def' => '',
        'req' => true,
      ),
      61 => 
      array (
        'key' => 'p12Url',
        'def' => '',
        'req' => true,
      ),
      62 => 
      array (
        'key' => 'pinP12',
        'def' => '',
        'req' => true,
      ),
      63 => 
      array (
        'key' => 'inXml',
        'def' => '',
        'req' => false,
      ),
      64 => 
      array (
        'key' => 'tipodoc',
        'def' => '',
        'req' => true,
      ),
      65 => 
      array (
        'key' => 'grant_type',
        'def' => '',
        'req' => true,
      ),
      66 => 
      array (
        'key' => 'client_id',
        'def' => '',
        'req' => true,
      ),
      67 => 
      array (
        'key' => 'client_secret',
        'def' => '',
        'req' => false,
      ),
      68 => 
      array (
        'key' => 'username',
        'def' => '',
        'req' => true,
      ),
      69 => 
      array (
        'key' => 'password',
        'def' => '',
        'req' => true,
      ),
      70 => 
      array (
        'key' => 'token',
        'def' => '',
        'req' => true,
      ),
      71 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      72 => 
      array (
        'key' => 'fecha',
        'def' => '',
        'req' => true,
      ),
      73 => 
      array (
        'key' => 'emi_tipoIdentificacion',
        'def' => '',
        'req' => true,
      ),
      74 => 
      array (
        'key' => 'emi_numeroIdentificacion',
        'def' => '',
        'req' => false,
      ),
      75 => 
      array (
        'key' => 'recp_tipoIdentificacion',
        'def' => '',
        'req' => true,
      ),
      76 => 
      array (
        'key' => 'recp_numeroIdentificacion',
        'def' => '',
        'req' => true,
      ),
      77 => 
      array (
        'key' => 'comprobanteXml',
        'def' => '',
        'req' => true,
      ),
      78 => 
      array (
        'key' => 'client_id',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  'ND' => 
  array (
    'action' => 'allND',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'tipoDocumento',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'tipoCedula',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'cedula',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'codigoPais',
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
        'key' => 'situacion',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'terminal',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'sucursal',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'codigoSeguridad',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      10 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'fecha_emision',
        'def' => '',
        'req' => true,
      ),
      12 => 
      array (
        'key' => 'emisor_nombre',
        'def' => '',
        'req' => true,
      ),
      13 => 
      array (
        'key' => 'emisor_tipo_indetif',
        'def' => '',
        'req' => true,
      ),
      14 => 
      array (
        'key' => 'emisor_num_identif',
        'def' => '',
        'req' => true,
      ),
      15 => 
      array (
        'key' => 'nombre_comercial',
        'def' => '',
        'req' => true,
      ),
      16 => 
      array (
        'key' => 'emisor_provincia',
        'def' => '',
        'req' => false,
      ),
      17 => 
      array (
        'key' => 'emisor_canton',
        'def' => '',
        'req' => false,
      ),
      18 => 
      array (
        'key' => 'emisor_distrito',
        'def' => '',
        'req' => false,
      ),
      19 => 
      array (
        'key' => 'emisor_barrio',
        'def' => '',
        'req' => false,
      ),
      20 => 
      array (
        'key' => 'emisor_otras_senas',
        'def' => '',
        'req' => false,
      ),
      21 => 
      array (
        'key' => 'emisor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      22 => 
      array (
        'key' => 'emisor_tel',
        'def' => '',
        'req' => false,
      ),
      23 => 
      array (
        'key' => 'emisor_cod_pais_fax',
        'def' => '',
        'req' => false,
      ),
      24 => 
      array (
        'key' => 'emisor_fax',
        'def' => '',
        'req' => false,
      ),
      25 => 
      array (
        'key' => 'emisor_email',
        'def' => '',
        'req' => true,
      ),
      26 => 
      array (
        'key' => 'receptor_nombre',
        'def' => '',
        'req' => true,
      ),
      27 => 
      array (
        'key' => 'receptor_tipo_identif',
        'def' => '',
        'req' => true,
      ),
      28 => 
      array (
        'key' => 'receptor_num_identif',
        'def' => '',
        'req' => true,
      ),
      29 => 
      array (
        'key' => 'receptor_provincia',
        'def' => '',
        'req' => false,
      ),
      30 => 
      array (
        'key' => 'receptor_canton',
        'def' => '',
        'req' => false,
      ),
      31 => 
      array (
        'key' => 'receptor_distrito',
        'def' => '',
        'req' => false,
      ),
      32 => 
      array (
        'key' => 'receptor_barrio',
        'def' => '',
        'req' => false,
      ),
      33 => 
      array (
        'key' => 'receptor_cod_pais_tel',
        'def' => '',
        'req' => false,
      ),
      34 => 
      array (
        'key' => 'receptor_tel',
        'def' => '',
        'req' => false,
      ),
      35 => 
      array (
        'key' => 'receptor_cod_pais_fax',
        'def' => '',
        'req' => false,
      ),
      36 => 
      array (
        'key' => 'receptor_fax',
        'def' => '',
        'req' => false,
      ),
      37 => 
      array (
        'key' => 'receptor_email',
        'def' => '',
        'req' => true,
      ),
      38 => 
      array (
        'key' => 'condicion_venta',
        'def' => '',
        'req' => true,
      ),
      39 => 
      array (
        'key' => 'plazo_credito',
        'def' => '0',
        'req' => false,
      ),
      40 => 
      array (
        'key' => 'medio_pago',
        'def' => '',
        'req' => true,
      ),
      41 => 
      array (
        'key' => 'cod_moneda',
        'def' => '',
        'req' => true,
      ),
      42 => 
      array (
        'key' => 'tipo_cambio',
        'def' => '',
        'req' => true,
      ),
      43 => 
      array (
        'key' => 'total_serv_gravados',
        'def' => '',
        'req' => true,
      ),
      44 => 
      array (
        'key' => 'total_serv_exentos',
        'def' => '',
        'req' => true,
      ),
      45 => 
      array (
        'key' => 'total_merc_gravada',
        'def' => '',
        'req' => true,
      ),
      46 => 
      array (
        'key' => 'total_merc_exenta',
        'def' => '',
        'req' => true,
      ),
      47 => 
      array (
        'key' => 'total_gravados',
        'def' => '',
        'req' => true,
      ),
      48 => 
      array (
        'key' => 'total_exentos',
        'def' => '',
        'req' => true,
      ),
      49 => 
      array (
        'key' => 'total_ventas',
        'def' => '',
        'req' => true,
      ),
      50 => 
      array (
        'key' => 'total_descuentos',
        'def' => '',
        'req' => true,
      ),
      51 => 
      array (
        'key' => 'total_ventas_neta',
        'def' => '',
        'req' => true,
      ),
      52 => 
      array (
        'key' => 'total_impuestos',
        'def' => '',
        'req' => true,
      ),
      53 => 
      array (
        'key' => 'total_comprobante',
        'def' => '',
        'req' => true,
      ),
      54 => 
      array (
        'key' => 'otros',
        'def' => '',
        'req' => true,
      ),
      55 => 
      array (
        'key' => 'detalles',
        'def' => '',
        'req' => true,
      ),
      56 => 
      array (
        'key' => 'infoRefeTipoDoc',
        'def' => '',
        'req' => true,
      ),
      57 => 
      array (
        'key' => 'infoRefeNumero',
        'def' => '',
        'req' => true,
      ),
      58 => 
      array (
        'key' => 'infoRefeFechaEmision',
        'def' => '',
        'req' => true,
      ),
      59 => 
      array (
        'key' => 'infoRefeCodigo',
        'def' => '',
        'req' => true,
      ),
      60 => 
      array (
        'key' => 'infoRefeRazon',
        'def' => '',
        'req' => true,
      ),
      61 => 
      array (
        'key' => 'p12Url',
        'def' => '',
        'req' => true,
      ),
      62 => 
      array (
        'key' => 'pinP12',
        'def' => '',
        'req' => true,
      ),
      63 => 
      array (
        'key' => 'inXml',
        'def' => '',
        'req' => false,
      ),
      64 => 
      array (
        'key' => 'tipodoc',
        'def' => '',
        'req' => true,
      ),
      65 => 
      array (
        'key' => 'grant_type',
        'def' => '',
        'req' => true,
      ),
      66 => 
      array (
        'key' => 'client_id',
        'def' => '',
        'req' => true,
      ),
      67 => 
      array (
        'key' => 'client_secret',
        'def' => '',
        'req' => false,
      ),
      68 => 
      array (
        'key' => 'username',
        'def' => '',
        'req' => true,
      ),
      69 => 
      array (
        'key' => 'password',
        'def' => '',
        'req' => true,
      ),
      70 => 
      array (
        'key' => 'token',
        'def' => '',
        'req' => true,
      ),
      71 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      72 => 
      array (
        'key' => 'fecha',
        'def' => '',
        'req' => true,
      ),
      73 => 
      array (
        'key' => 'emi_tipoIdentificacion',
        'def' => '',
        'req' => true,
      ),
      74 => 
      array (
        'key' => 'emi_numeroIdentificacion',
        'def' => '',
        'req' => false,
      ),
      75 => 
      array (
        'key' => 'recp_tipoIdentificacion',
        'def' => '',
        'req' => true,
      ),
      76 => 
      array (
        'key' => 'recp_numeroIdentificacion',
        'def' => '',
        'req' => true,
      ),
      77 => 
      array (
        'key' => 'comprobanteXml',
        'def' => '',
        'req' => true,
      ),
      78 => 
      array (
        'key' => 'client_id',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
);
