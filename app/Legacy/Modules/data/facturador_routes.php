<?php

/**
 * Tabla de rutas del módulo facturador, extraída mecánicamente de
 * legacy/api/contrib/facturador/module.php (facturador_init()). La ruta
 * duplicada company_get_env se colapsa en una. No editar a mano.
 */
return array (
  0 => 
  array (
    'r' => 'info',
    'action' => 'module_info',
    'access' => 'open',
    'params' => 
    array (
    ),
  ),
  1 => 
  array (
    'r' => 'copy_master_tables',
    'action' => 'copyMasterTables',
    'access' => 'user',
    'params' => 
    array (
    ),
  ),
  2 => 
  array (
    'r' => 'companny_add_master_Consecutive',
    'action' => 'companny_add_master_Consecutive',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  3 => 
  array (
    'r' => 'getSucursales',
    'action' => 'getSucursales',
    'access' => 'user',
    'params' => 
    array (
    ),
  ),
  4 => 
  array (
    'r' => 'addSucursales',
    'action' => 'addSucursales',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'numeroSucursal',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'nombreSucursal',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  5 => 
  array (
    'r' => 'add_terminal',
    'action' => 'addTerminal',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'numeroTerminal',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'nombreTerminal',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'idSucursal',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  6 => 
  array (
    'r' => 'getTerminales',
    'action' => 'getTerminales',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idSucursal',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  7 => 
  array (
    'r' => 'getUserPermissionById',
    'action' => 'getUserPermissionById',
    'access' => 'user',
    'params' => 
    array (
    ),
  ),
  8 => 
  array (
    'r' => 'getUsersCompanny',
    'action' => 'getUsersCompanny',
    'access' => 'user',
    'params' => 
    array (
    ),
  ),
  9 => 
  array (
    'r' => 'get_active_receiver',
    'action' => 'getActiveReceiver',
    'access' => 'company',
    'params' => 
    array (
    ),
  ),
  10 => 
  array (
    'r' => 'get_receiver_by_id',
    'action' => 'getReceiverById',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idReceptor',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  11 => 
  array (
    'r' => 'get_vouchers',
    'action' => 'getVouchers',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'env',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  12 => 
  array (
    'r' => 'getProductByCode',
    'action' => 'getProductByCode',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'codigo',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'sucursal',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  13 => 
  array (
    'r' => 'get_inventory',
    'action' => 'getInventory',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'sucursal',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  14 => 
  array (
    'r' => 'backup_user',
    'action' => 'backUpUser',
    'access' => 'user',
    'params' => 
    array (
    ),
  ),
  15 => 
  array (
    'r' => 'get_all_privinces',
    'action' => 'getAllProvinces',
    'access' => 'open',
    'params' => 
    array (
    ),
  ),
  16 => 
  array (
    'r' => 'get_type_of_id',
    'action' => 'getTypeOfId',
    'access' => 'open',
    'params' => 
    array (
    ),
  ),
  17 => 
  array (
    'r' => 'get_cantons',
    'action' => 'getCantons',
    'access' => 'open',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idProvince',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  18 => 
  array (
    'r' => 'get_district',
    'action' => 'getDistrict',
    'access' => 'open',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idProvince',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idCanton',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  19 => 
  array (
    'r' => 'delete_reciver',
    'action' => 'deleteReciver',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idReceptor',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  20 => 
  array (
    'r' => 'company_stag_users',
    'action' => 'companyStagUsers',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'userName',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'password',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'pinCerti',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'downloadCode',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  21 => 
  array (
    'r' => 'company_change_env',
    'action' => 'companyChangeEnv',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'envProduccion',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  22 => 
  array (
    'r' => 'company_get_env',
    'action' => 'companyGetEnv',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  23 => 
  array (
    'r' => 'company_prod_users',
    'action' => 'companyProdUsers',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'userName',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'password',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'pinCerti',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'downloadCode',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  24 => 
  array (
    'r' => 'get_stag_credentials',
    'action' => 'getStagCredentials',
    'access' => 'user',
    'params' => 
    array (
    ),
  ),
  25 => 
  array (
    'r' => 'get_prod_credentials',
    'action' => 'getProdCredentials',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  26 => 
  array (
    'r' => 'get_prod_companny_credentials',
    'action' => 'getProdCompannyCredentials',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  27 => 
  array (
    'r' => 'add_companny_reciver',
    'action' => 'addCompannyReciver',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'nombreCliente',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'numeroCedula',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'tipoCedula',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'telefono',
        'def' => '',
        'req' => false,
      ),
      5 => 
      array (
        'key' => 'idProvincia',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'idCanton',
        'def' => '',
        'req' => true,
      ),
      7 => 
      array (
        'key' => 'idDistrito',
        'def' => '',
        'req' => true,
      ),
      8 => 
      array (
        'key' => 'idBarrio',
        'def' => '',
        'req' => true,
      ),
      9 => 
      array (
        'key' => 'otrasSenas',
        'def' => '',
        'req' => true,
      ),
      10 => 
      array (
        'key' => 'nombreComercial',
        'def' => '',
        'req' => true,
      ),
      11 => 
      array (
        'key' => 'correoPrincipal',
        'def' => '',
        'req' => false,
      ),
      12 => 
      array (
        'key' => 'copiasCorreo',
        'def' => '',
        'req' => false,
      ),
      13 => 
      array (
        'key' => 'numeroFax',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  28 => 
  array (
    'r' => 'get_stag_companny_credentials',
    'action' => 'getStagCompannyCredentials',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  29 => 
  array (
    'r' => 'companny_add_voucher',
    'action' => 'companny_add_voucher',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'clave',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'consecutivo',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'estado',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'xmlEnviadoBase64',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'tipoDocumento',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'respuestaMHBase64',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'idReceptor',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'env',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  30 => 
  array (
    'r' => 'companny_updateConsecutive',
    'action' => 'companny_updateConsecutive',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'tipoDocumento',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'env',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  31 => 
  array (
    'r' => 'get_companny_information',
    'action' => 'getCompannyInformation',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  32 => 
  array (
    'r' => 'get_companny_information_admin',
    'action' => 'getCompannyInformationAdmin',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  33 => 
  array (
    'r' => 'compannyUpdateTipoCambio',
    'action' => 'compannyUpdateTipoCambio',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'tipoCambio',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  34 => 
  array (
    'r' => 'compannyUpdateLocation',
    'action' => 'compannyUpdateLocation',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idProvincia',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'idCanton',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'idDistrito',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'idBarrio',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'sennas',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  35 => 
  array (
    'r' => 'compannyUpdateInformation',
    'action' => 'compannyUpdateInformation',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'nombre',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'nombreComercial',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'email',
        'def' => '',
        'req' => true,
      ),
      4 => 
      array (
        'key' => 'codigoPais',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'fax',
        'def' => '',
        'req' => false,
      ),
      6 => 
      array (
        'key' => 'tipoCedula',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'cedula',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'telefono',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  36 => 
  array (
    'r' => 'getCompannyLocationInformation',
    'action' => 'getCompannyLocationInformation',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idProvincia',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idCanton',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'idDistrito',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'idBarrio',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  37 => 
  array (
    'r' => 'get_neighborhood',
    'action' => 'getNeighborhood',
    'access' => 'open',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idProvince',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idCanton',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'idDistrito',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  38 => 
  array (
    'r' => 'inser_to_log_table',
    'action' => 'insertToLogTable',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'json',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  39 => 
  array (
    'r' => 'companny_getMyInfo',
    'action' => 'companny_getMyInfo',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  40 => 
  array (
    'r' => 'companny_users_getMyDetails',
    'action' => 'companny_users_getMyDetails',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  41 => 
  array (
    'r' => 'companny_getMyConsecutive',
    'action' => 'companny_getMyConsecutive',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'tipoComprobante',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'env',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  42 => 
  array (
    'r' => 'companny_users_register',
    'action' => 'companny_users_registerNew',
    'access' => 'user',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'fullName',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'userName',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'email',
        'def' => '',
        'req' => true,
      ),
      3 => 
      array (
        'key' => 'about',
        'def' => '',
        'req' => false,
      ),
      4 => 
      array (
        'key' => 'country',
        'def' => '',
        'req' => true,
      ),
      5 => 
      array (
        'key' => 'pwd',
        'def' => '',
        'req' => true,
      ),
      6 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  43 => 
  array (
    'r' => 'companny_users_get_my_details',
    'action' => 'companny_users_getMyDetails',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  44 => 
  array (
    'r' => 'get_tipo_impuesto',
    'action' => 'getTipoImpuesto',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  45 => 
  array (
    'r' => 'getUnid',
    'action' => 'getUnid',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  46 => 
  array (
    'r' => 'addInventaryProduct',
    'action' => 'addInventaryProduct',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => false,
      ),
      1 => 
      array (
        'key' => 'nombre',
        'def' => '',
        'req' => false,
      ),
      2 => 
      array (
        'key' => 'descripcion',
        'def' => '',
        'req' => false,
      ),
      3 => 
      array (
        'key' => 'unidadMedida',
        'def' => '',
        'req' => false,
      ),
      4 => 
      array (
        'key' => 'precioVenta',
        'def' => '',
        'req' => false,
      ),
      5 => 
      array (
        'key' => 'idImpuesto',
        'def' => '',
        'req' => false,
      ),
      6 => 
      array (
        'key' => 'cantidadImpuesto',
        'def' => '',
        'req' => false,
      ),
      7 => 
      array (
        'key' => 'codigoBarras',
        'def' => '',
        'req' => false,
      ),
      8 => 
      array (
        'key' => 'disponible',
        'def' => '',
        'req' => false,
      ),
      9 => 
      array (
        'key' => 'sucursal',
        'def' => '',
        'req' => false,
      ),
    ),
  ),
  47 => 
  array (
    'r' => 'companny_users_recover_pwd',
    'action' => 'companny_users_recoverPwd',
    'access' => 'open',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'userName',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  48 => 
  array (
    'r' => 'companny_users_update_profile',
    'action' => 'companny_users_updateProfile',
    'access' => 'company',
    'params' => 
    array (
    ),
  ),
  49 => 
  array (
    'r' => 'users_log_me_out',
    'action' => 'companny_users_logMeOut',
    'access' => 'company',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
  50 => 
  array (
    'r' => 'companny_users_logMeIn',
    'action' => 'companny_users_logMeIn',
    'access' => 'open',
    'params' => 
    array (
      0 => 
      array (
        'key' => 'idMasterUser',
        'def' => '',
        'req' => true,
      ),
      1 => 
      array (
        'key' => 'userName',
        'def' => '',
        'req' => true,
      ),
      2 => 
      array (
        'key' => 'pwd',
        'def' => '',
        'req' => true,
      ),
    ),
  ),
);
