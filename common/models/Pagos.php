<?php

namespace common\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "g04_pagos".
 *
 * @property int $g04_id ID
 * @property int $g01_id Promotor
 * @property int $g02_id Cliente
 * @property int $g03_id Prestamo
 * @property int $g04_cantidad Monto
 * @property string $g04_fecha Fecha pago
 * @property int $g04_semana Semana
 * @property string $g04_created Fecha alta
 * @property int $user_id Usuario alta
 *
 * @property G01Promotores $g01
 * @property G02Clientes $g02
 * @property User $user
 * @property G03Prestamos $g03
 */
class Pagos extends \yii\db\ActiveRecord
{

    public $g01_id;
    public $g02_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g04_pagos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['g01_id', 'g02_id', 'g04_cantidad', 'g04_fecha', 'g04_semana', 'user_id'], 'required'],
            //['g01_id', 'required', 'message' => Yii::$app->params['error_g01_id']],
            //['g02_id', 'required', 'message' => Yii::$app->params['error_g02_id']],
            ['g04_cantidad', 'required', 'message' => Yii::$app->params['error_g04_cantidad']],
            ['g04_fecha', 'required', 'message' => Yii::$app->params['error_g04_fecha']],
            ['g04_semana', 'required', 'message' => Yii::$app->params['error_g04_semana']],

            ['g04_cantidad', 'integer', 'message' => Yii::$app->params['error_numer']], 

            [['g01_id', 'g02_id', 'g03_id', 'g04_cantidad', 'g04_semana', 'user_id','g04_status'], 'integer'],
            [['g04_fecha', 'g04_created'], 'safe'],
            [['g01_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promotores::className(), 'targetAttribute' => ['g01_id' => 'g01_id']],
            [['g02_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['g02_id' => 'g02_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['g03_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prestamos::className(), 'targetAttribute' => ['g03_id' => 'g03_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g04_id' => 'ID',
            'g01_id' => 'Promotor',
            'g02_id' => 'Cliente',
            'g03_id' => 'ID prestamo',
            'g04_cantidad' => 'Monto',
            'g04_fecha' => 'Fecha pago',
            'g04_semana' => 'Semana',
            'g04_created' => 'Fecha alta',
            'user_id' => 'Usuario alta',
            'g04_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getG01()
    {
        return $this->hasOne(Promotores::className(), ['g01_id' => 'g01_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getG02()
    {
        return $this->hasOne(Clientes::className(), ['g02_id' => 'g02_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getG03()
    {
        return $this->hasOne(Prestamos::className(), ['g03_id' => 'g03_id']);
    }

    /*
        Prestamos -> view
        Pagos -> index
        PagosController
    */
    public static function getListSemanas($g02_tipo = Clientes::TIPO_MOROSO){
        
        $array = [];
        $semanas = $g02_tipo == Clientes::TIPO_BUEN_CLIENTE ? 14 : 15;

        for ($i=1 ; $i <= $semanas ; $i++) { 
            $array[$i] = Yii::$app->params['txtSemanasPagos'] . $i;
        }

        return $array;
    }

    /*
        Prestamos -> view
        pagos -> index
    */
    public static function getSemana($g04_semana){
        return Pagos::getListSemanas()[$g04_semana];
    }

    /*
        PagosController
    */
    public static function getAllPagosPrestamo($g03_id){
        return Pagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id])->all();
    }

    /*
        PrestamosController
    */
    public static function getExistePagosPrestamo($g03_id){
        return Pagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id])->count() > 0;
    }

    /*  
        PagosController
    */
    public static function getCountPagosPrestamo($g03_id){
        return Pagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id])->count();
    }

    /*
        Prestamos -> index
    */
    public static function getTotalPagos($g03_id){
        return Pagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id])->sum('g04_cantidad');
    }

    public static function getTotalPagosPrestamos($g02_id){
        $total = 0;

        $model = Prestamos::find()->where(['g03_status' => Yii::$app->params['value_active'], 'g02_id' => $g02_id])->all();

        foreach ($model as $row) {
            $total += Pagos::getTotalPagos($row->g03_id);
        }

        return $total;
    }

    public static function getUltimoPago($g03_id){
        return Pagos::find()->where(['g04_status' => Yii::$app->params['value_active'], 'g03_id' => $g03_id])->orderBy(['g04_fecha' => SORT_DESC])->one();
    }
}
