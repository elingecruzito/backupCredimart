<?php

namespace frontend\controllers;

use Yii;
use common\models\Promotores;
use common\models\search\PromotoresSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\search\ClientesSearch;
use common\models\Clientes;
use common\models\Movimientos;
use common\models\Utilerias;
/**
 * PromotoresController implements the CRUD actions for Promotores model.
 */
class PromotoresController extends Controller
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
     * Lists all Promotores models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PromotoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Promotores model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id /* g01_id */)
    {
        //Se optiene un filtro para poder visualizar los clientes que tiene el promotor seleccionado
        $searchModelClientes = new ClientesSearch();
        $dataProviderClientes = $searchModelClientes->searchPromotor(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModelClientes' => $searchModelClientes,
            'dataProviderClientes' => $dataProviderClientes,
        ]);
    }

    /**
     * Creates a new Promotores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Promotores();

        if ($model->load(Yii::$app->request->post())) {
            //Se obtiene el id del usuario que esta creando el nuevo cliente
            $model->user_id = Yii::$app->user->id; 
            if($model->save()){ 

                //Se genera y se guarda un nuevo movimiento de insercion
                $movimientos = new Movimientos();
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_INSERT;
                $movimientos->c01_id_tabla = $model->g01_id;
                $movimientos->c01_tabla = Promotores::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();

                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmNuevoPromotor']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Promotores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            //Se genera un nuevo movimiento de modificacion
            $movimientos = new Movimientos();
            $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Promotores::tableName());
            //Se obtiene el id del usuario que esta modificando el nuevo cliente
            $model->user_id = Yii::$app->user->id;
            if($model->save()){

                //Se guarda el nuevo movimiento    
                $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Promotores::tableName());
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_UPDATE;
                $movimientos->c01_id_tabla = $model->g01_id;
                $movimientos->c01_tabla = Promotores::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();

                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmModificarPromotor']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Promotores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //Se valida que no tenga ningun cliente activo el promotor
        if(Clientes::getExisteClientes($id)){
            //Se manda un mensaje de error por tener un cliente activo
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);
            return $this->redirect(['index']);
        }

        //Se elimina logicamente el promotor
        $model->g01_status = Yii::$app->params['value_delete'];
        $model->user_id = Yii::$app->user->id;

         //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Promotores::tableName());

        if($model->save()){

            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Promotores::tableName());
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g01_id;
            $movimientos->c01_tabla = Promotores::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Promotores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promotores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promotores::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
