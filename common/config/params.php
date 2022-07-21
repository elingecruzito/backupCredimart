<?php

return [
    'adminEmail' => 'andresandy01@hotmail.com',
    'supportEmail' => 'andresandy01@hotmail.com',
    'senderEmail' => 'andresandy0101@gmail.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,

    /*
    'path_host' => 'https://system.credimart.com.mx',
    'path_photo_save' => '/home/credimar/public_html/img/clientes',
    'photo_view' => 'credimart.com.mx/',
    */
    

    'path_host' => 'http://localhost/credimart',
    'path_photo_save' => 'G:\Proyectos\localhost\credimart\frontend\web\img\clientes',
    'photo_view' => 'localhost/credimart/',
    

    /* Parametro para el archivo vendor/yiisoft/yii2/grid/ActionColum */
    'msjDelete' => 'Estas seguro de eliminar este registro ?',
    'msjDeleteConfirm' => 'El registro ha sido eliminado exitosamente.',
    'msjDeleteError' => 'El registro no se ha podido eliminar.',

    'error_telefono_number' => 'Debe de ingresar un numero valido.',
    'error_numer' => 'El campo debe de ser un valor numerico.',
    'error_user_id' => 'El campo user_id no debe de estar en blanco.',

    'value_delete' => 1,
    'value_active' => 0,

    'routes_solo_clientes' => ['site/index', 'site/reset-password','clientes/index','clientes/create', 'clientes/update','clientes/delete'],
    'routes_solo_pagos' => ['site/index', 'site/reset-password', 'pagos/index','pagos/create','pagos/view','pagos/delete'],

    'msjConfirmLastMovimiento' => 'Esta seguro de reetablecer este registro ?',
    'msjConfirmNewMovimiento' => 'Esta seguro de modificar este registro ?',

    'msjMovimientosQuery' => 'El registro ha sido modificado exitosamente.',
    'msjErrorMovimientosQuery' => 'Ocurrio un error al modificar el registro.',

    'backDashboard' => 'Regresar al inicio',


//-----------------------------------------Usuarios-----------------------------------------

    'txtTitleIndexUsuarios' => 'Usuarios',
    'txtTitleCrearUsuarios' => 'Añadir usuario',
    'txtTitleModificarUsuarios' => 'Modificar usuario',
    'txtTitleViewUsuarios' => 'Usuario: ',
    'txtTitleChangePassword' => 'Cambiar contraseña',

    'btnGuardarUser' => 'Guardar',

    'btnAñadirUsuario' => '<i class="fa fa-fw fa-plus"></i> Añadir usuario',

    'user_tipo_administrador' => 'Administrador',
    'user_tipo_clientes' => 'Solo clientes',
    'user_tipo_pagos' => 'Solo pagos',

    'lbl_username' => 'Usuario',
    'lbl_email' => 'Correo electronico',
    'lbl_password' => 'Contraseña',
    'lbl_type' => 'Tipo de usuario',
    'id' => 'Id',
    'created_at' => 'Fecha alta',

    'error_unique_username' => 'El nombre de usuario ya ha sido registrado.',
    'error_unique_email' => 'El emial ya ha sido registrado.',

    'error_username' => 'El campo usuario no debe de estar en blanco.',
    'error_email' => 'El campo email no debe de estar en blanco.',
    'error_password' => 'El campo contraseña no debe de estar en blanco.',
    'error_password_longitud' => 'La contraseña debe de ser de al menos 6 caracteres',
    'error_type' => 'El campo tipo de usuario no debe de estar en blanco.',

    'msjConfirmNuevoUsuario' => 'Se agrego exitosamente un nuevo usuario.',
    'msjConfirmModificarUsuario' => 'Se modifico exitosamente el usuario.',
    'msjChangePasswordForm' => 'Porfavor seleccione su nueva contraseña.',
    'msjChangePassword' => 'Nueva contraseña guardada.',


//-----------------------------------------Login-----------------------------------------
    
    'msjLogin' => 'Logearse para iniciar secion',
    'txtUsername' => 'Usuario',
    'txtPassword' => 'Contraseña',
    'checkRemember' => 'Recordarme',
    'btnSingUp' => 'Entrar',

    'msjErrorValidarPassword' => 'Usuario y/o contraseña incorrecto.',
    'errorUsername' => 'El campo usuario no debe de estar en blanco.',
	'errorPassword' => 'El campo contraseña no debe de estar en blanco.',

//-----------------------------------------Promotores-----------------------------------------
    'txtTitleIndexPromotores' => 'Promotores',
    'txtTitleCrearPromotores' => 'Añadir promotor',
    'txtTitleModificarPromotores' => 'Modificar promotor',
    'txtTitleViewPromotor' => 'Promotor: ',

    'btnAñadirPromotor' => '<i class="fa fa-fw fa-plus"></i> Añadir promotor',
    'btnGuardarPromotor' => 'Guardar',

    'error_g01_nombre' => 'El campo nombre no debe de estar en blanco.',
    'error_g01_paterno' => 'El campo apellido paterno no debe de estar en blanco.',
    'error_g01_materno' => 'El campo apellido materno no debe de estar en blanco.',
    'error_g01_domicilio' => 'El campo domicilio no debe de estar en blanco.',
    'error_g01_telefono' => 'El campo telefono no debe de estar en blanco.',
    

    'msjConfirmNuevoPromotor' => 'Se agrego exitosamente un nuevo promotor.',
    'msjConfirmModificarPromotor' => 'Se modifico exitosamente el promotor.',

//-----------------------------------------Clientes-----------------------------------------

    'txtTitleIndexClientes' => 'Clientes',
    'txtTitleCrearCliente' => 'Añadir cliente',
    'txtTitleModificarCliente' => 'Modificar cliente',
    'txtTitleViewCliente' => 'Cliente: ',

    'btnAñadirCliente' => '<i class="fa fa-fw fa-plus"></i> Añadir cliente',
    'btnGuardarCliente' => 'Guardar',

    'error_g02_nombre' => 'El campo nombre no puede estar en blanco.',
    'error_g02_paterno' => 'El campo apellido paterno no puede estar en blanco.',
    'error_g02_materno' => 'El campo apellido materno no puede estar en blanco.',
    'error_g02_domicilio' => 'El campo domicilio no puede estar en blanco.',
    'error_g02_telefono' => 'El campo telefono no puede estar en blanco.',
    'error_g02_maximo' => 'El campo prestamo maximo no debe de estar en blanco.',
    'error_g01_id' => 'El campo promotor no puede estar en blanco.',
    'error_g02_nombre_aval' => 'El campo nombre del aval no puede estar en blanco.',
    'error_g02_paterno_aval' => 'El campo apellido paterno del aval no puede estar en blanco.',
    'error_g02_materno_aval' => 'El campo apellido materno del aval no puede estar en blanco.',
    'error_g02_domicilio_aval' => 'El campo domicilio del aval no puede estar en blanco.',
    'error_g02_telefono_aval' => 'El campo telefono del aval no puede estar en blanco.',
    'error_g02_fecha_solicitud' => 'El campo fecha solicitud no puede estar en blanco.',
    'error_g02_tipo' => 'El campo tipo de usuario no debe de estar en blanco.',

    'msjConfirmNuevoCliente' => 'Se agrego exitosamente un nuevo cliente.',
    'msjConfirmModificarCliente' => 'Se modifico exitosamente el cliente.',

//-----------------------------------------Prestamos-----------------------------------------

    'txtTitleIndexPrestamos' => 'Prestamos',
    'txtTitleCrearPrestamos' => 'Añadir prestamo',
    'txtTitleModificarPrestamos' => 'Modificar prestamo',
    'txtTitleViewPrestamos' => 'Prestamo: ',

    'btnAñadirPrestamos' => '<i class="fa fa-fw fa-plus"></i> Añadir prestamo',
    'btnGuardarPrestamos' => 'Guardar',

    'msjConfirmNuevoPrestamos' => 'Se agrego exitosamente un nuevo prestamo.',
    'msjConfirmModificarPrestamos' => 'Se modifico exitosamente el prestamo.',
    'msjErrorPrestamoActual' => 'Este cliente ya tiene un prestamo pendiente.',
    'msjErrorPrestamoMaximo' => 'Su credito maximo es de ',

    'txtEstadoPendiente' => 'Pendiente',
    'txtEstadoLiquidado' => 'Liquidado',
    'txtEstadoCerrado' => 'Cerrado',

    'error_g02_id' => 'El campo cliente no puede estar en blanco.',
    'error_g03_monto' => 'El campo monto no puede estar en blanco.',
    'error_g03_abono' => 'El campo abono no puede estar en blanco.',
    'error_g03_total' => 'El campo total no puede estar en blanco.',
    'error_g03_fecha' => 'El campo fecha no puede estar en blanco.',
    'error_comparate' => 'El monto total debe de ser mayor o igual al prestamo.',

//-----------------------------------------Pagos-----------------------------------------

    'txtTitleIndexPagos' => 'Pagos',
    'txtTitleCrearPagos' => 'Añadir pago',
    'txtTitleModificarPagos' => 'Modificar pago',
    'txtTitleViewPagos' => 'Pago de: ',

    'btnAñadirPagos' => '<i class="fa fa-fw fa-plus"></i> Añadir pago',
    'btnGuardarPagos' => 'Guardar',

    'msjConfirmNuevoPagos' => 'Se agrego exitosamente un nuevo pago.',
    'msjConfirmModificarPagos' => 'Se modifico exitosamente el pago.',

    'txtSemanasPagos' => 'Semana ',

    'error_g04_cantidad' => 'El campo cantidad no debe de estar en blanco.',
    'error_g04_fecha' => 'El campo fecha no debe de estar en blanco.',
    'error_g04_semana' => 'El campo semana no debe de estar en blanco.',


//-----------------------------------------Movimientos-----------------------------------------

    'txtTitleIndexMovimientos' => 'Movimientos',
    'txtTitleViewMovimientos' => 'Movimiento N* ',

//-----------------------------------------Notificaciones-----------------------------------------
    'msjNotificacionNuevoClientes' => 'Se ha registrado un nuevo cliente con Id ',
    'msjNotificacionNuevoPrestamo' => 'Se ha registrado un nuevo prestamo con Id ',
    'msjNotificacionProximoPago' => 'El pago del cliente {cliente} no ha sido realizado.',

    'titleNotificacionNuevoClientes' => 'Nuevo cliente ',
    'titleNotificacionNuevoPrestamo' => 'Nuevo prestamo',
    'titleNotificacionProximoPago' => 'Pago no realizado.',

    'msjLoginNotificaciones' => 'Usted tiene {notificaciones} notificaciones nuevas.',
];
