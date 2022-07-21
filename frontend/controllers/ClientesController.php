<?php

namespace frontend\controllers;

use Yii;
use common\models\Clientes;
use common\models\search\ClientesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Utilerias;

use common\models\Prestamos;
use common\models\Movimientos;
use common\models\search\PrestamosSearch;
use yii\web\UploadedFile;
/**
 * ClientesController implements the CRUD actions for Clientes model.
 */
class ClientesController extends Controller
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
     * Lists all Clientes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clientes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //Se optiene los prestamos que tiene el cliente seleccionado
        $searchModelPrestamos = new PrestamosSearch();
        $dataProviderPrestamos = $searchModelPrestamos->searchClientes(Yii::$app->request->queryParams, $model->g02_id);
        //Se genera la direccion de imagen para poder mostrarla 
        $model->file = Clientes::getPathImg() . $model->g02_img;
        
        return $this->render('view', [
            'model' => $model,
            'searchModelPrestamos' => $searchModelPrestamos,
            'dataProviderPrestamos' => $dataProviderPrestamos,
        ]);
    }

    /**
     * Creates a new Clientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clientes();

        if ($model->load(Yii::$app->request->post())) {
            //Se coniverte la fecha al formato soportado por la base de datos
            $model->g02_fecha_solicitud = Utilerias::convertirFecha($model->g02_fecha_solicitud);
            //Se optiene el usuario que dio de alta el registro
            $model->user_id = Yii::$app->user->id;
            //Se optiene la instacia de la aricho de imagen
            $model->file = UploadedFile::getInstance($model, 'file');
            //Si existe una imagen 
            if($model->file != ''){
                //Se optiene un sombre random para la imagen
                $imageName = md5(Utilerias::getRandomString());
                //Se optiene genera la direccion completa de donde se va a guardar la imagen 
                $fileName = Yii::$app->params['path_photo_save'].'/'.$imageName.'.'.$model->file->extension;
                //Se obtiene el nombre de la imagen para guardarla en la base de datos
                $model->g02_img = $imageName.'.'.$model->file->extension;
                //Se guarda la imagen en el servidor 
                $model->file->saveAs($fileName);
            }else{
                //Se optiene la imagen default a mostrar para el cliente
                $model->g02_img = 'default.png';
            }

            if($model->save()){
                //Se genera y se guarda un nuevo movimiento de insercion
                $movimientos = new Movimientos();
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_INSERT;
                $movimientos->c01_id_tabla = $model->g02_id;
                $movimientos->c01_tabla = Clientes::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();

                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmNuevoCliente']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Clientes model.
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
            $model->g02_fecha_solicitud = Utilerias::convertirFecha($model->g02_fecha_solicitud);
            //Se optiene la instancia del archido imagen 
            $model->file = UploadedFile::getInstance($model, 'file');
            //Si existe una imagen
            if($model->file != ''){
                //Se genera un nombre random
                $imageName = md5(Utilerias::getRandomString());
                //Se genera la direccion completa del servidor en donse de va a guardar
                $fileName = Yii::$app->params['path_photo_save'].'/'.$imageName.'.'.$model->file->extension;
                //Se obtiene el nombre de la imagen que se guardara en la base de datos
                $model->g02_img = $imageName.'.'.$model->file->extension;
                //Se guarda la imagen en el servidor
                $model->file->saveAs($fileName);
            }

            //Se genera un nuevo movimiento de modificacion
            $movimientos = new Movimientos();
            $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Clientes::tableName());
            

            if($model->save()){

                //Se guarda el nuevo movimiento    
                $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Clientes::tableName());
                $movimientos->c01_tipo = Movimientos::MOVIMIENTO_UPDATE;
                $movimientos->c01_id_tabla = $model->g02_id;
                $movimientos->c01_tabla = Clientes::tableName();
                $movimientos->user_id = Yii::$app->user->id;
                $movimientos->save();
                //Se manda un mensaje de confirmacion
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmModificarCliente']);
                return $this->redirect(['index']);
            }
        }

        //Se convierte la fecha para poder mostrarla en el formulario
        $model->g02_fecha_solicitud = Utilerias::invertirFecha($model->g02_fecha_solicitud);
        //Se optiene la direccion completa par apoder mostrar la imagen 
        $model->file = Clientes::getPathImg() .$model->g02_img;

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Clientes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //Se valida que el cliente no tenga prestamos 
        if(Prestamos::getPrestamosActuales($id)){
            //Se manda un mensaje de error informando que tiene uno o mas prestamos
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);
            return $this->redirect(['index']);
        }

        //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Clientes::tableName());
            

        //Se elimina el registro logicamente
        $model->g02_status = Yii::$app->params['value_delete'];

        if($model->save()){

            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Clientes::tableName());
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g02_id;
            $movimientos->c01_tabla = Clientes::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }
        else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);


        return $this->redirect(['index']);
    }

    /*Metodo llamado desde promotores -> view */
    public function actionDeletefrompromotor($id)
    {
        $model = $this->findModel($id);
        //Se optiene el ID del promotor que esta eliminando el registro
        $promotor = $model->g01_id;
        //Se valida que el cliente no tenga prestamos
        if(Prestamos::getPrestamosActuales($id)){
            //Se manda un mensaje de error informando que tiene uno o mas prestamos
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);
            return $this->redirect(['promotores/view', 'id' => $promotor]);
        }

        //Se genera un nuevo movimiento de modificacion
        $movimientos = new Movimientos();
        $movimientos->c01_old_row = Utilerias::generateQueryBitacora($this->findModel($id), Clientes::tableName());

        //Se elimina el registro logicamente
        $model->g02_status = Yii::$app->params['value_delete'];

        if($model->save()){

            //Se guarda el nuevo movimiento    
            $movimientos->c01_new_row = Utilerias::generateQueryBitacora($this->findModel($id), Clientes::tableName());
            $movimientos->c01_tipo = Movimientos::MOVIMIENTO_DELETE;
            $movimientos->c01_id_tabla = $model->g02_id;
            $movimientos->c01_tabla = Clientes::tableName();
            $movimientos->user_id = Yii::$app->user->id;
            $movimientos->save();

            //Se manda un mensaje de confirmacion
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
        }
        else
            Yii::$app->session->setFlash('error', Yii::$app->params['msjDeleteError']);


        return $this->redirect(['promotores/view', 'id' => $promotor]);
    }

    /**
     * Finds the Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clientes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
