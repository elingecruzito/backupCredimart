<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "g06_notificaciones".
 *
 * @property int $g06_id ID
 * @property string $g06_message Mensaje
 * @property string $g06_date Fecha
 * @property int $g06_status Estado
 * @property int $g06_user Usuario
 */
class Notificaciones extends \yii\db\ActiveRecord
{
    const STATUS_SIN_LEER = 0;
    const STATUS_LEIDA = 1;

    const TIPO_NUEVO_CLIENTE = 1;
    const TIPO_NUEVO_PRESTAMO = 2;
    const TIPO_PROXIMO_PAGAR = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g06_notificaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g06_message', 'g06_user', 'g06_tipo'], 'required'],
            [['g06_message'], 'string'],
            [['g06_date'], 'safe'],
            [['g06_status', 'g06_user', 'g06_tipo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g06_id' => 'ID',
            'g06_message' => 'Mensaje',
            'g06_date' => 'Fecha',
            'g06_tipo' => 'Tipo',
            'g06_status' => 'Estado',
            'g06_user' => 'Usuario',
        ];
    }

    public static function getNuevasNotificaciones($user_id){
        return Notificaciones::find()->where(['g06_user' => $user_id])->orderBy(['g06_date' => SORT_DESC])->limit(5)->all();
    }

    public static function getTotalNuevas($user_id){
        return Notificaciones::find()->where(['g06_status' => Notificaciones::STATUS_SIN_LEER, 'g06_user' => $user_id])->count();
    }

    public static function getListIconsNotificacion(){
        return [
            Notificaciones::TIPO_NUEVO_CLIENTE => 'fa fa-users text-aqua',
            Notificaciones::TIPO_NUEVO_PRESTAMO => 'fa fa-shopping-cart text-green',
            Notificaciones::TIPO_PROXIMO_PAGAR => 'fa fa-warning text-yellow',
        ];
    }

    public static function getIconNotificacion($g06_tipo){
        return Notificaciones::getListIconsNotificacion()[$g06_tipo];
    }

    public static function setAllNotificaciones($user_id){
        Notificaciones::updateAll(['g06_status' => Notificaciones::STATUS_LEIDA],['g06_user' => $user_id]);
    }

    public static function getAllDatesNotificaciones($user_id){
        $query = 'SELECT DATE_FORMAT(g06_notificaciones.g06_date, "%Y-%m-%d") AS g06_date FROM g06_notificaciones WHERE g06_notificaciones.g06_user = '. $user_id .' GROUP BY DATE_FORMAT(g06_notificaciones.g06_date, "%Y-%m-%d") ORDER BY DATE_FORMAT(g06_notificaciones.g06_date, "%Y-%m-%d") DESC';

        return Yii::$app->db->createCommand($query)->queryAll();
    }

    public static function getNotificacionesOfTheDay($user_id, $date){
        return Notificaciones::find()
                        ->where(['g06_user' => $user_id])
                        ->andFilterWhere(['like', 'g06_date',  $date])
                        ->orderBy(['g06_date' => SORT_DESC])
                        ->all();

    }


    public static function getIconForListNotificaciones($g06_tipo){
        return [
            Notificaciones::TIPO_NUEVO_CLIENTE => 'fa fa-users',
            Notificaciones::TIPO_NUEVO_PRESTAMO => 'fa fa-shopping-cart',
            Notificaciones::TIPO_PROXIMO_PAGAR => 'fa fa-warning',
        ][$g06_tipo];
    }

    public static function getTitleNotificacion($g06_tipo){
        return [
            Notificaciones::TIPO_NUEVO_CLIENTE => Yii::$app->params['titleNotificacionNuevoClientes'],
            Notificaciones::TIPO_NUEVO_PRESTAMO => Yii::$app->params['titleNotificacionNuevoPrestamo'],
            Notificaciones::TIPO_PROXIMO_PAGAR => Yii::$app->params['titleNotificacionProximoPago'],
        ][$g06_tipo];
    }
}
