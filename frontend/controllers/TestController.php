<?php

namespace frontend\controllers;

use Yii;
use app\web\NotFoundHttpException;
use app\filters\VerbFilter;

use common\models\Utilerias;
use common\models\Prestamos;
use common\models\Pagos;
use common\models\Clientes;
use common\models\ReportePrestamos;
use common\models\User;
use common\models\Notificaciones;

use kartik\mpdf\Pdf;

/**

 */
class TestController extends Controller{


    /* curl www.system.credimart.com.mx/test/notificaciones?password=NbjU9WGrEsBq */
    public function actionNotificaciones($password){

        if($password != 'NbjU9WGrEsBq'){
            return ;
        }

        //Se obtiene la fecha actual
        $today = date('Y-m-d'); 
        //Si el dia de la fecha actual no es viernes o sabado
        if(!Utilerias::getValidPayDay($today)){
            return ;
        }

        //Se obtiene una lista de todos los clientes vigentes
        $clientes = Clientes::getListClientes();
    
        foreach ($clientes as $key => $cliente) {
            //Se obtiene la el ultimo prestamo del cliente
            $prestamo = Prestamos::getPrestamoAcual($key);
            //Se crea una variable axiliar
            $existePago = false;

            //Si existe un prestamo actual
            if(!is_null($prestamo)){
                //Se obitne los datos del ultimo pago del prestamoa actual y pendiente
                $pago = Pagos::getUltimoPago($prestamo->g03_id);

                //Si exite un pago del prestamo
                if(!is_null($pago)){
                    //Se obtiene el valor de la funcion que valida que la fecha del ultimo pago este dentro de la semana actual
                    $existePago = Utilerias::getFechaEnRangoSemana($today, $pago->g04_fecha);
                }

                //Si no existe un pago de la semana actual
                if(!$existePago){
                    //Se obtiene una lista de todos los usuarios vigentes
                    $usuarios = User::getListUserAcivos();
                    foreach ($usuarios as $usuario) {
                        //Se crea una nueva notificafion
                        $modelNotificaciones = new Notificaciones();
                        $modelNotificaciones->g06_user = $usuario->id;
                        $modelNotificaciones->g06_message = str_replace('{cliente}', $cliente, Yii::$app->params['msjNotificacionProximoPago']);
                        $modelNotificaciones->g06_tipo = Notificaciones::TIPO_PROXIMO_PAGAR;
                        $modelNotificaciones->save();
                    }
                }
            }
        }
    }

    /* curl www.system.credimart.com.mx/test/close-prestamos?password=NbjU9WGrEsBq */
    public function actionClosePrestamos($password){

        if($password != 'NbjU9WGrEsBq'){
            return ;
        }

        //Se obtiene una lista de clientes
        $clientes = Clientes::getListClientes();
        //Se obtiene la fecha actual
        $today = date('Y-m-d');

        foreach ($clientes as $key => $cliente) {
            //Se obtiene el ultimo prestamo del cliente
            $prestamo = Prestamos::getUltimoPrestamo($key);
            //Si existe un prestamo 
            if(!is_null($prestamo)){
                //Se opbitne los datos del cliente
                $clientedata = Clientes::findModel($key);
                //Se valida que tipo de cliente es y cuantas semanas de pago debe de tener
                $semanas = $clientedata->g02_tipo == Clientes::TIPO_MOROSO ? 15 : 14;
                //Se obtiene la fecha de corte 
                $dateCortePrestamo = date('Y-m-d', strtotime($prestamo->g03_fecha . ' +' . $semanas . ' week'));

                //Si la fecha d ecorte es menos o igual al dia actual
                if(strtotime($dateCortePrestamo) <= strtotime($today)){

                    //Se obitnene las semanas de pago
                    $semanaspagos = Pagos::getListSemanas($clientedata->g02_tipo);
                    //Se obtiene todos los pagos que ha realizado el cliente
                    $pagos = Pagos::getAllPagosPrestamo($prestamo->g03_id);

                    //Se obtienen todas las semanas de pago faltantes
                    foreach ($pagos as $keypagos => $valuepagos) {
                        unset($semanaspagos[$valuepagos->g04_semana]);
                    }

                    //Se crean los pagos faltantes
                    foreach ($semanaspagos as $keysemana => $valuesemana) {

                        $modelPagos = new Pagos();
                        $modelPagos->g01_id = $clientedata->g01_id;
                        $modelPagos->g02_id = $key;
                        $modelPagos->g03_id = $prestamo->g03_id;
                        $modelPagos->g04_cantidad = 0;
                        $modelPagos->g04_fecha = $today;
                        $modelPagos->g04_semana = $keysemana;
                        $modelPagos->user_id = 1;
                        $modelPagos->save();
                    }

                    //Se Cierra el prestamo
                    $prestamo->g03_estado = Prestamos::ESTADO_CERRADO;
                    $prestamo->save();

                    //Se cambia el tipo de cliente por tener un prestamo cerrado
                    $clientedata->g02_tipo = Clientes::TIPO_MOROSO;
                    $clientedata->save();
                }

            }
        }

    }


    /* curl www.system.credimart.com.mx/test/send-email?password=NbjU9WGrEsBq */

    public function actionSendEmail($password){

        if($password != 'NbjU9WGrEsBq'){
            return ;
        }

        $model = ReportePrestamos::find()->all();

        $result = Yii::$app->mailer
            ->compose(['html' => 'emailVerify-html'],['model' => $model,])
            ->setFrom(['soporte@credimart.com.mx' => Yii::$app->name])
            ->setTo('andresandy0101@gmail.com')
            ->setSubject('Reporte '. Yii::$app->name .' ' . date('d-m-Y') )
            ->send();

        if($result){
            echo "Message sended !";
        }else{
            echo "Error";
        }
    }

}