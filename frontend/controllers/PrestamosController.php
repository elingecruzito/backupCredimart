<?php

namespace frontend\controllers;

use Yii;
use common\models\Prestamos;
use common\models\search\PrestamosSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Utilerias;
use common\models\Pagos;
use common\models\Movimientos;
use common\models\Clientes;
use common\models\search\ClientesPagosSearch;
/**
 * PrestamosController implements the CRUD actions for Prestamos model.
 */
class PrestamosController extends Controller
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
     * Lists all Prestamos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrestamosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prestamos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        //Se optiene la lista de pagos que tiene el prestamo
        $searchModelPagos = new ClientesPagosSearch();
        $dataProviderPagos = $searchModelPagos->searchPrestamos(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModelPagos' => $searchModelPagos,
            'dataProviderPagos' => $dataProviderPagos,
        ]);
    }

    /**
     * Creates a new Prestamos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Prestamos();

        if ($model->load(Yii::$app->request->post())) {
            //Se le da el valor de prestamo pendiente por defauklt
            $model->g03_estado = Prestamos::ESTADO_PENDIENTE;
            //Se convierte la fecha al formato soportado por la base de datos
            $model->g03_fecha = Utilerias::convertirFecha($model->g03_fecha);
            //Se optiene el usuario que esta haciendo la insercion
            $model->user_id = Yii::$app->user->id;

            //Se valida que el usuario no tenga algun prestamo activo
            if(Prestamos::getPrestamosActuales($model->g02_id)){
                //Se manda un mensaje de error informado de que el cliente ya tiene un prestamo activo
                Yii::$app->session->setFlash('error', Yii::$app->params['msjErrorPrestamoActual']);
                $model->g03_fecha = Utilerias::invertirFecha($model->g03_fecha);
            //El monto de prestamo maximo es menor al monto de prestamo
            }else if(Clientes::findModel($model->g02_id)->g02_maximo < $model->g03_monto ){ 
                //Se manda un mensaje de error informado de que el cliente ya tiene un prestamo activo
                Yii::$app->session->setFlash('error', Yii::$app->params['msjErrorPrestamoMaximo'] . Utilerias::formatMoney(Clientes::findModel($model->g02_id)->g02_maximo));
                $model->g03_fecha = Utilerias::invertirFecha($model->g03_fecha);
            }else if($model->save()){

                //Se genera un nuevo movimiento de insercion
                $movimientos = new Movimientos();
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_INSERT;
                $movimientos->c01_id_tabla = $model->g03_id;
                $movimientos->c01_tabla = Prestamos::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();

                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmNuevoPrestamos']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Prestamos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            //Se convierte la fecha al formato soportado por la base de datos
            $model->g03_fecha = Utilerias::convertirFecha($model->g03_fecha);
            //Se genera un nuevo movimiento de modificacion
            $movimientos = new Movimientos();
            $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Prestamos::tableName());

            //El monto de prestamo maximo es menor al monto de prestamo
            if(Clientes::findModel($model->g02_id)->g02_maximo < $model->g03_monto ){ 
                //Se manda un mensaje de error informado de que el cliente ya tiene un prestamo activo
                Yii::$app->session->setFlash('error', Yii::$app->params['msjErrorPrestamoMaximo'] . Utilerias::formatMoney(Clientes::findModel($model->g02_id)->g02_maximo));
            }else if($model->save()){
                //Se guarda el nuevo movimiento    
                $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Prestamos::tableName());
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_UPDATE;
                $movimientos->c01_id_tabla = $model->g03_id;
                $movimientos->c01_tabla = Prestamos::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();

                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmModificarPrestamos']);
                return $this->redirect(['index']);
            }
        }

        //Se convierte la fecha para poderla mostrar en el formulario
        $model->g03_fecha = Utilerias::invertirFecha($model->g03_fecha);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Prestamos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //Se valida que el prestamo no tenga pagos activos
         if(Pagos::getExistePagosPrestamo($id)){
            //Se manda un mensaje de error informado que ya tiene pagos
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);
            return $this->redirect(['index']);
        }

        //Se elimina logicamente el registro
        $model->g03_status = Yii::$app->params['value_delete'];

        //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Prestamos::tableName());

        if($model->save()){

            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Prestamos::tableName());    
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g03_id;
            $movimientos->c01_tabla = Prestamos::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }
        else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);

        return $this->redirect(['index']);
    }

    /*Metodo llamado desde clientes -> view */
    public function actionDeletefromcliente($id){

        $model = $this->findModel($id);
        //Se valida que el prestamo no tenga un pago activo
        if(Pagos::getExistePagosPrestamo($id)){
            //Se manda un mensaje de error informado que ya tiene pagos
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);
            return $this->redirect(['clientes/view', 'id' => $model->g02_id]);
        }

        //Se elimina logicamente el registro
        $model->g03_status = Yii::$app->params['value_delete'];

        //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Prestamos::tableName());
        
        if($model->save()){

            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Prestamos::tableName()); 
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g03_id;
            $movimientos->c01_tabla = Prestamos::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }
        else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);

        return $this->redirect(['clientes/view', 'id' => $model->g02_id]);
    }

    /**
     * Finds the Prestamos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prestamos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prestamos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
