<?php

namespace common\models;

use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "g02_clientes".
 *
 * @property int $g02_id ID
 * @property string $g02_nombre Nombre (s)
 * @property string $g02_paterno Apellido paterno
 * @property string $g02_materno Apellido materno
 * @property string $g02_domicilio Domicilio 
 * @property int $g02_telefono Telefono
 * @property int $g01_id Promotor
 * @property string $g02_nombre_aval Nombre(s) del aval
 * @property string $g02_paterno_aval Apellido paterno del aval
 * @property string $g02_materno_aval Apellido materno del aval
 * @property string $g02_domicilio_aval Direccion del aval
 * @property int $g02_telefono_aval Telefono del aval
 * @property string $g02_fecha_solicitud Fecha de solicitud
 * @property int $g02_tipo Tipo cliente
 * @property string $g02_created Fecha alta
 * @property int $user_id Usuario alta
 *
 * @property G01Promotores $g01
 * @property User $user
 * @property G03Prestamos[] $g03Prestamos
 * @property G04Pagos[] $g04Pagos
 */
class Clientes extends \yii\db\ActiveRecord
{
    public $file;
    const TIPO_BUEN_CLIENTE = 1;
    const TIPO_MOROSO = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g02_clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['g02_nombre', 'g02_paterno', 'g02_materno', 'g02_domicilio', 'g02_telefono', 'g01_id', 'g02_nombre_aval', 'g02_paterno_aval', 'g02_materno_aval', 'g02_domicilio_aval', 'g02_telefono_aval', 'g02_fecha_solicitud', 'user_id'], 'required'],
            ['g02_nombre', 'required', 'message' => Yii::$app->params['error_g02_nombre']],
            ['g02_paterno', 'required', 'message' => Yii::$app->params['error_g02_paterno']],
            ['g02_materno', 'required', 'message' => Yii::$app->params['error_g02_materno']],
            ['g02_domicilio', 'required', 'message' => Yii::$app->params['error_g02_domicilio']],
            //['g02_telefono', 'required', 'message' => Yii::$app->params['error_g02_telefono']],
            ['g02_maximo', 'required', 'message' => Yii::$app->params['error_g02_maximo']],
            ['g01_id', 'required', 'message' => Yii::$app->params['error_g01_id']],
            ['g02_nombre_aval', 'required', 'message' => Yii::$app->params['error_g02_nombre_aval']],
            ['g02_paterno_aval', 'required', 'message' => Yii::$app->params['error_g02_paterno_aval']],
            ['g02_materno_aval', 'required', 'message' => Yii::$app->params['error_g02_materno_aval']],
            ['g02_domicilio_aval', 'required', 'message' => Yii::$app->params['error_g02_domicilio_aval']],
            //['g02_telefono_aval', 'required', 'message' => Yii::$app->params['error_g02_telefono_aval']],
            ['g02_fecha_solicitud', 'required', 'message' => Yii::$app->params['error_g02_fecha_solicitud']],
            ['user_id', 'required', 'message' => Yii::$app->params['error_user_id']],
            ['g02_tipo', 'required', 'message' => Yii::$app->params['error_g02_tipo']],

            //[['g02_telefono','g02_telefono_aval'], 'integer', 'message' => Yii::$app->params['error_telefono_number']],
            //[['g02_telefono','g02_telefono_aval'], 'string', 'length' => [10, 10], 'tooShort' => Yii::$app->params['error_telefono_number'], 'tooLong' => Yii::$app->params['error_telefono_number']],
            [['g02_telefono','g02_telefono_aval'], 'match', 'pattern' => "/^(\D*)?(\d{3})(\D*)?(\d{3})(\D*)?(\d{4})$/", 'message' => Yii::$app->params['error_telefono_number']],

            [['g02_nombre', 'g02_paterno', 'g02_materno', 'g02_img', 'g02_domicilio', 'g02_nombre_aval', 'g02_paterno_aval', 'g02_materno_aval', 'g02_domicilio_aval'], 'string'],
            [['g01_id', 'g02_tipo', 'user_id','g02_status', 'g02_maximo'], 'integer', 'message' => Yii::$app->params['error_numer']],
            [['g02_fecha_solicitud', 'g02_created', 'file'], 'safe'],
            [['g01_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promotores::className(), 'targetAttribute' => ['g01_id' => 'g01_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g02_id' => 'ID',
            'g02_nombre' => 'Nombre (s)',
            'g02_paterno' => 'Apellido paterno',
            'g02_materno' => 'Apellido materno',
            'g02_img' => 'Foto',
            'file' => 'Foto',
            'g02_domicilio' => 'Domicilio ',
            'g02_telefono' => 'Telefono',
            'g02_maximo' => 'Prestamo maximo',
            'g01_id' => 'Promotor',
            'g02_nombre_aval' => 'Nombre(s) del aval',
            'g02_paterno_aval' => 'Apellido paterno del aval',
            'g02_materno_aval' => 'Apellido materno del aval',
            'g02_domicilio_aval' => 'Direccion del aval',
            'g02_telefono_aval' => 'Telefono del aval',
            'g02_fecha_solicitud' => 'Fecha de solicitud',
            'g02_tipo' => 'Tipo cliente',
            'g02_created' => 'Fecha alta',
            'user_id' => 'Usuario alta',
            'g02_status' => 'Status',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getG03Prestamos()
    {
        return $this->hasMany(Prestamos::className(), ['g02_id' => 'g02_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getG04Pagos()
    {
        return $this->hasMany(Pagos::className(), ['g02_id' => 'g02_id']);
    }

    /*
        Promotores -> view
        Clientes -> index
    */
    public static function getTiposClientes(){
        return [Clientes::TIPO_BUEN_CLIENTE => 'Buen cliente', Clientes::TIPO_MOROSO => 'Moroso' ];
    }

    /*
        Promotores -> view
        Clientes -> index
        Clientes -> view
    */
    public static function getTipoCliente($g02_tipo){
        return Clientes::getTiposClientes()[$g02_tipo];
    }

    /*
        Prestamos -> form
        Prestamos -> index
        Pagos -> index
    */
    public static function getListClientes(){
        return ArrayHelper::map(Clientes::find()->where(['g02_status' => Yii::$app->params['value_active']])->all(), 'g02_id', function($model) {
                return $model['g02_nombre'] . ' ' . $model['g02_paterno'] . ' ' . $model['g02_materno'];
            }
        );
    }

    /*
        Prestamos -> index
        Prestamos -> view
        Pagos -> index
        Pagos -> view
    */
    public static function getCliente($g02_id){
        return Clientes::getListClientes()[$g02_id];
    }

    /*
        PagosController
    */
    public static function getListClientesPromotores($g01_id){

        return Clientes::find()->where(['g02_status' => Yii::$app->params['value_active'], 'g01_id' => $g01_id])->all();
    }

    /*
        PromotoresController
    */
    public static function getExisteClientes($g01_id){
        return Clientes::find()->where(['g02_status' => Yii::$app->params['value_active'], 'g01_id' => $g01_id])->count() > 0;
    }

    /*
        ClientesController
    */
    public static function getPathImg(){
        return 'http://www.' . Yii::$app->params['photo_view'] . 'img/clientes/';
    }

    /*
        PagosController
    */
    public static function findModel($id){
        return Clientes::findOne($id);
    }
}
