<?php
/**
 * Created by PhpStorm.
 * User: ernesto
 * Date: 1/19/17
 * Time: 11:58 AM
 */

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\Notificaciones;
use common\models\Perfiles;

class Controller extends \yii\web\Controller
{
    public function beforeAction($event)
    {   
        $routes = ['login' /*'signup', 'error', 'request-password-reset', 'request-password', 'reset-password'*/];
        $action = Yii::$app->controller->action->id;

        $route = Yii::$app->controller->id . "/". $action;

        if(Yii::$app->user->isGuest){
            if(!in_array($action, $routes)){
                return $this->redirect(Yii::$app->params['path_host'].'/site/login');
            }
        }else{
            if(!Perfiles::getAccess($route, Yii::$app->user->id)){
                Yii::$app->session->setFlash('error', 'Por cuestiones de seguridad tu usuario no tiene permitido acceder a seccion.');
                return $this->redirect(Yii::$app->params['path_host']);  
            }
        }
        /*else{
            if(Yii::$app->user->identity->type == User::ADMINISTRADOR){
                return $this->redirect(Yii::$app->params['path_host_super'].'/site/login');
            }
        }*/
        if($event->id == 'error' && Yii::$app->user->isGuest){
            $this->layout = 'error';
        }

        //Yii::$app->session['notificaciones'] = Notificaciones::getAllNotificationsIcon(Yii::$app->user->id);
        //Yii::$app->session['notificaciones_activas'] = Notificaciones::getActivesNotificaciones(Yii::$app->user->id);

        return parent::beforeAction($event);
    }
}
