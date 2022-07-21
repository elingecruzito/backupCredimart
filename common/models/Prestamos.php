<?php

namespace common\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "g03_prestamos".
 *
 * @property int $g03_id ID
 * @property int $g02_id Cliente
 * @property int $g03_monto Monto prestamo
 * @property int $g03_abono Abono semanal
 * @property int $g03_total Total a pagar
 * @property string $g03_fecha Fecha prestamo
 * @property int $g03_estado Estado
 * @property string $g03_created Fecha alta
 * @property int $user_id Usuario alta
 *
 * @property G02Clientes $g02
 * @property User $user
 */
class Prestamos extends \yii\db\ActiveRecord
{

    const ESTADO_PENDIENTE = 1;
    const ESTADO_LIQUIDADO = 2;
    const ESTADO_CERRADO = 3;
    public $totalPagos;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g03_prestamos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['g02_id', 'g03_monto', 'g03_abono', 'g03_total', 'g03_fecha', 'user_id'], 'required'],

            ['g02_id',  'required', 'message' => Yii::$app->params['error_g02_id']],
            ['g03_monto',  'required', 'message' => Yii::$app->params['error_g03_monto']],
            ['g03_abono',  'required', 'message' => Yii::$app->params['error_g03_abono']],
            ['g03_total',  'required', 'message' => Yii::$app->params['error_g03_total']],
            ['g03_fecha',  'required', 'message' => Yii::$app->params['error_g03_fecha']],
            ['user_id', 'required', 'message' => Yii::$app->params['error_user_id']],

            [['g03_monto', 'g03_abono', 'g03_total'], 'integer', 'message' => Yii::$app->params['error_numer'] ], 
            ['g03_total', 'compare', 'compareAttribute'=>"g03_monto", 'operator'=>">=", 'message' => Yii::$app->params['error_comparate'] ],

            [['g02_id', 'g03_monto', 'g03_abono', 'g03_total', 'g03_estado', 'user_id'], 'integer'],
            [['g03_fecha', 'g03_created', 'g03_status'], 'safe'],
            [['g02_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['g02_id' => 'g02_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g03_id' => 'ID',
            'g02_id' => 'Cliente',
            'g03_monto' => 'Prestamo',
            'g03_abono' => 'Abono',
            'g03_total' => 'Total',
            'totalPagos' => 'Total Pagado',
            'g03_fecha' => 'Fecha prestamo',
            'g03_estado' => 'Estado',
            'g03_created' => 'Fecha alta',
            'user_id' => 'Usuario alta',
            'g03_status' => 'Status'
        ];
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

    public static function getListEstadosPrestamos(){
        return [
            Prestamos::ESTADO_PENDIENTE => Yii::$app->params['txtEstadoPendiente'], 
            Prestamos::ESTADO_LIQUIDADO => Yii::$app->params['txtEstadoLiquidado'],
            Prestamos::ESTADO_CERRADO => Yii::$app->params['txtEstadoCerrado']
        ];
    }

    /*
        Clientes -> view
        Prestamos -> index
        Prestamos -> view
    */
    public static function getEstadoPrestamo($g03_id){
        $estados = Prestamos::getListEstadosPrestamos();
        return $estados[$g03_id];
    }

    /*
        ClientesController
        PrestamosController
    */ 
    public static function getPrestamosActuales($g02_id){
        return Prestamos::find()->where(['g03_status' => Yii::$app->params['value_active'], 'g02_id' => $g02_id, 'g03_estado' => Prestamos::ESTADO_PENDIENTE])->count() > 0;
    }

    /*
        PagosController
    */
    public static function getPrestamoAcual($g02_id){
        return Prestamos::find()->where(['g03_status' => Yii::$app->params['value_active'], 'g02_id' => $g02_id, 'g03_estado' => Prestamos::ESTADO_PENDIENTE])->one();
    }

    /*
        Prestamos -> view
        Pagos -> index
        PagosController
    */
    public static function findModel($g03_id){
        return Prestamos::findOne($g03_id);
    }

    public static function getAllPrestamos(){
        return Prestamos::find()->where(['g03_status' => Yii::$app->params['value_active'], 'g03_estado' => Prestamos::ESTADO_PENDIENTE ])->all();
    }

    /*
        Clientes -> view
    */
    public static function getTotalPrestamos($g02_id){
        return Prestamos::find()->where(['g03_status' => Yii::$app->params['value_active'], 'g02_id' => $g02_id])->sum('g03_total');
    }

    public static function getUltimoPrestamo($g02_id){
        return Prestamos::find()->where(['g03_status' => Yii::$app->params['value_active'], 'g02_id' => $g02_id])->orderBy(['g03_fecha' => SORT_DESC])->one();
    }
}
