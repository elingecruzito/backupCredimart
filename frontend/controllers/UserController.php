<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\search\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\SignupForm;
use common\models\Perfiles;
use common\models\search\MovimientosSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new MovimientosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('user_id = ' . $id);

        return $this->render('view', [
            'model' =>  User::findOne($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmNuevoUsuario']);
            return $this->redirect(['index']);
        }

        return $this->render('/site/signup', [
            'title' => Yii::$app->params['txtTitleCrearUsuarios'],
            'model' => $model,
            'isNewRecord' => true,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $user = User::findOne($id);
            $user->username = $model->username;
            $perfil = Perfiles::findOne(['user_id' => $id]);
            $perfil->g05_tipo = $model->type;

            if($user->save() && $perfil->save()){
                Yii::$app->session->setFlash('success', Yii::$app->params['msjConfirmModificarUsuario']);
                return $this->redirect(['index']);
            }
        }

        return $this->render('/site/signup', [
            'title' => Yii::$app->params['txtTitleModificarUsuarios'],
            'model' => $model,
            'isNewRecord' => false,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = User::findOne($id);
        $model->status = User::STATUS_DELETED;
    
        if($model->save()){
            Yii::$app->session->setFlash('success', Yii::$app->params['msjDeleteConfirm']);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($user = User::findOne($id)) !== null && ($perfil = Perfiles::findOne(['user_id' => $id])) !== null) {

            $model = new SignupForm();
            $model->username = $user->username;
            //$model->email = $user->email;
            $model->type = $perfil->g05_tipo;


            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
