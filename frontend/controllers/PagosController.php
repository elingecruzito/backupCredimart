<?php

namespace frontend\controllers;

use Yii;
use common\models\Pagos;
use common\models\search\PagosSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Clientes;
use common\models\Prestamos;
use common\models\Movimientos;
use common\models\ClientesPagos;
use common\models\Utilerias;
use common\models\search\ClientesPagosSearch;

/**
 * PagosController implements the CRUD actions for Pagos model.
 */
class PagosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Pagos models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new PagosSearch();
        $searchModel = new ClientesPagosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pagos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModelPagos($id),
        ]);
    }

    /**
     * Creates a new Pagos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pagos();

        if ($model->load(Yii::$app->request->post())) {        
            //Se optiene el usuario que esta haciendo la insercion
            $model->user_id = Yii::$app->user->id;
            //Se convierte la fecha al formato soportado por la bae de datos
            $model->g04_fecha = Utilerias::convertirFecha($model->g04_fecha);
            //Se optiene el prestamo actual que tiene el cliente seleccionado
            $model->g03_id = Prestamos::getPrestamoAcual($model->g02_id)->g03_id;

            if($model->save()){

                //Se genera un nuevo movimiento de insercion
                $movimientos = new Movimientos();
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_INSERT;
                $movimientos->c01_id_tabla = $model->g04_id;
                $movimientos->c01_tabla = Pagos::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();

                //Se manda llamar el metodo que actualiza el tipo de cliente 
                $this->updateTypeCliente($model->g04_id);
                //Se manda llamar el metodo que actualiza el estado del prestamo
                $this->updateEstadoPrestamo($model->g03_id);

                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmNuevoPagos']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pagos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
                return $this->redirect(['view', 'id' => $model->g04_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }*/

    /**
     * Deletes an existing Pagos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //Se elimina logicamente el registro
        $model->g04_status = Yii::$app->params['value_delete'];
        
         //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Pagos::tableName());

        if($model->save()){
            
            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Pagos::tableName());   
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g04_id;
            $movimientos->c01_tabla = Pagos::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda llamar el metodo que actualiza el tipo de cliente 
            $this->updateTypeCliente($model->g04_id);
            //Se manda llamar el metodo que actualiza el estado del prestamo
            $this->updateEstadoPrestamo($model->g03_id);

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }
        else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);

        return $this->redirect(['index']);
    }

    /*Este metodo es llamado por una funcion ajax en el formulario*/
    public function actionClienteslist($id /* g01_id */){
        //Se optiene la lista de cliente que tiene el promotor
        $clientes = Clientes::getListClientesPromotores($id);
        //Se genera un nuevo array de clientes
        $arrayClientes = [];

        foreach ($clientes as $row) {
            //Se inserta un nuevo cliente 
            $arrayClientes[] = ['g02_id' => $row->g02_id , 'g02_nombre' => $row->g02_nombre . ' ' . $row->g02_paterno . ' ' . $row->g02_materno ];
        }

        //Se genera un JSON que interpretara el metodo ajax
        echo json_encode($arrayClientes);
    }

    /*Este metodo es llamado por una funcion ajax en el formulario*/
    public function actionSemanaspagosclientes($id /*g02_id*/){
        //Se optienen los datos del cliente seleccionado
        $cliente = Clientes::findModel($id);
        //Se optiene los datos del prestamos acutal que tiene el cliente
        $prestamo = Prestamos::getPrestamoAcual($id);
        //Se optienen una lista de pagos que ya ha echo el cliente
        $pagos = Pagos::getAllPagosPrestamo($prestamo->g03_id);
        //Se optiene la lista de pagos
        $listaPagos = Pagos::getListSemanas($cliente->g02_tipo);
        //Se genera un nuevo array de semanas a pagar
        $arraySemanas = [];

        foreach ($pagos as $row) {
            //Se eliminan las semanas que ya estan pagadas por el cliente
            unset($listaPagos[$row->g04_semana]);
        }
        
        foreach ($listaPagos as $key => $value) {
            //Se genera inserta las semanas que el cliente debe pagar
            $arraySemanas[] = ['index' => $key, 'value' => $value];
        }

        //Se genera un JSON que interpretara el metodo JSON
        echo json_encode($arraySemanas);
    }

    /*Este metodo es llamado por una funcion ajax en el formulario*/
    public function actionAbono($id /*g02_id*/){
        //Se optiene el abono que debe de tener el min
        $prestamo = Prestamos::getPrestamoAcual($id);
        echo $prestamo->g03_abono;
    }

    /*Metodo mandado llamar desde prestamos -> view */
     public function actionDeletefromprestamo($id){
        $model = $this->findModel($id);
        //Se elimina logicamente el registro
        $model->g04_status = Yii::$app->params['value_delete'];

         //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Pagos::tableName());
        
        if($model->save()){
            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Pagos::tableName()); 
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g04_id;
            $movimientos->c01_tabla = Pagos::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }
        else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);

        return $this->redirect(['prestamos/view', 'id' => $model->g03_id]);
    }

    /**
     * Finds the Pagos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pagos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pagos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelPagos($id)
    {
        if (($model = ClientesPagos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function updateTypeCliente($id){
        //Se optiene el pago actual
        $model = $this->findModelPagos($id);
        //Se optienen todos los pagos del prestamo
        $allPagos = Pagos::getAllPagosPrestamo($model->g03_id);
        //Se optiene el cliente del prestamo
        $modelCliente = Clientes::findModel($model->g02_id);
        //Se setea el tipo un tipo de cliente default
        $modelCliente->g02_tipo = Clientes::TIPO_BUEN_CLIENTE;
        //Se optiene los datos del prestamo
        $modelPrestamo = Prestamos::findModel($model->g03_id);

        foreach ($allPagos as $row) {
            //Se valida si la fecha de pago fue en los dias establecidos
            if(!Utilerias::getValidPayDay($row->g04_fecha))
                //El tipo de cliente cambia el tiempo de cliente
                $modelCliente->g02_tipo = Clientes::TIPO_MOROSO;
            //Se valida que el monto del pago sea igual al monto de abono
            if($row->g04_cantidad < $modelPrestamo->g03_abono)
                //El tipo de cliente cambia el tiempo de cliente
                $modelCliente->g02_tipo = Clientes::TIPO_MOROSO;
        }
        //Se guarda guarda el cliente
        return $modelCliente->save();

    }

    protected function updateEstadoPrestamo($g03_id){
        //Se optiene el numero de pagos que se han echo al prestamo 
        $totalPagos = Pagos::getCountPagosPrestamo($g03_id);
        //Se optienen los datos del prestamo
        $prestamo = Prestamos::findModel($g03_id);
        //Se optiene los datos del cliente
        $cliente = Clientes::findModel($prestamo->g02_id);
        //Se setea un estao de prestamo default
        $prestamo->g03_estado = Prestamos::ESTADO_PENDIENTE;
        //Se valida si el el estado del cliente para saber cuantos pagos debe de tener el prestamo
        $pagosParaPagar = $cliente->g02_tipo == Clientes::TIPO_BUEN_CLIENTE ? 14 : 15;

        //Si el numero de pagos es igual a los pagos que se deben obtener 
        if($totalPagos == $pagosParaPagar)
            //Se cambia el estado del prestamo
            $prestamo->g03_estado = Prestamos::ESTADO_LIQUIDADO;

        //Se guarda el prestamo
        $prestamo->save();
            
    }
}
