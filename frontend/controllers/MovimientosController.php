<?php

namespace frontend\controllers;

use Yii;
use common\models\Movimientos;
use common\models\search\MovimientosSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Utilerias;

/**
 * MovimientosController implements the CRUD actions for Movimientos model.
 */
class MovimientosController extends Controller
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
     * Lists all Movimientos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MovimientosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Movimientos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionLastRow($id){
        $model = $this->findModel($id);
        $query = Utilerias::generateQuery($model->c01_old_row, $model->c01_tabla);
        if(Movimientos::executeQueryMovimientos($query)){

            //Se guarda el nuevo movimiento    
            /*$movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Promotores::tableName());
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g01_id;
            $movimientos->c01_tabla = Promotores::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();
            */

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjMovimientosQuery']);
        }else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjErrorMovimientosQuery']);

        return $this->redirect(['index']);
    }

    public function actionNewRow($id){
        $model = $this->findModel($id);
        $query = Utilerias::generateQuery($model->c01_new_row, $model->c01_tabla);
        if(Movimientos::executeQueryMovimientos($query)){

            //Se guarda el nuevo movimiento    
            /*$movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Promotores::tableName());
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g01_id;
            $movimientos->c01_tabla = Promotores::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();
            */

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjMovimientosQuery']);
        }else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjErrorMovimientosQuery']);

        return $this->redirect(['index']);
    }


    /**
     * Finds the Movimientos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Movimientos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Movimientos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
