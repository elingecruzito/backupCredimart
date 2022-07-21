<?php

namespace frontend\controllers;

use Yii;
use common\models\Notificaciones;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NotificacionesController implements the CRUD actions for Notificaciones model.
 */
class NotificacionesController extends Controller
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
     * Lists all Notificaciones models.
     * @return mixed
     */
    public function actionIndex()
    {
        Notificaciones::setAllNotificaciones(Yii::$app->user->id);
        
        $model = Notificaciones::getAllDatesNotificaciones(Yii::$app->user->id);
        return $this->render('index',[
            'model' => $model
        ]);
    }
    
    /* notificaciones/notificaciones-leidas?user_id=$user_id */
    public function actionNotificacionesLeidas($user_id){
        Notificaciones::setAllNotificaciones($user_id);
    }

}
