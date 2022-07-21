<?php

namespace common\models;

use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "g01_promotores".
 *
 * @property int $g01_id ID
 * @property string $g01_nombre Nombre(s)
 * @property string $g01_paterno Apellido parterno
 * @property string $g01_materno Apellido materno
 * @property string $g01_domicilio Domicilio
 * @property int $g01_telefono Telefono
 * @property string $g01_created Fecha creacion
 * @property int $user_id Usuario creacion
 *
 * @property User $user
 * @property G02Clientes[] $g02Clientes
 * @property G04Pagos[] $g04Pagos
 */
class Promotores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'g01_promotores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['g01_nombre', 'g01_paterno', 'g01_materno', 'g01_domicilio', 'g01_telefono', 'user_id'], 'required'],
            ['g01_nombre', 'required' , 'message' => Yii::$app->params['error_g01_nombre']],
            ['g01_paterno', 'required' , 'message' => Yii::$app->params['error_g01_paterno']],
            ['g01_materno', 'required' , 'message' => Yii::$app->params['error_g01_materno']],
            ['g01_domicilio', 'required' , 'message' => Yii::$app->params['error_g01_domicilio']],
            //['g01_telefono', 'required' , 'message' => Yii::$app->params['error_g01_telefono']],
            ['user_id', 'required' , 'message' => Yii::$app->params['error_user_id']],
            [['g01_nombre', 'g01_paterno', 'g01_materno', 'g01_domicilio','g01_telefono'], 'string'],
            [['user_id', 'g01_status'], 'integer'],
            //['g01_telefono', 'integer', 'message' => Yii::$app->params['error_telefono_number']],
            //['g01_telefono', 'string', 'length' => [11, 11], 'tooShort' => Yii::$app->params['error_telefono_number'], 'tooLong' => Yii::$app->params['error_telefono_number']],
            ['g01_telefono', 'match', 'pattern' => "/^(\D*)?(\d{3})(\D*)?(\d{3})(\D*)?(\d{4})$/", 'message' => Yii::$app->params['error_telefono_number']],
            [['g01_created'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g01_id' => 'ID',
            'g01_nombre' => 'Nombre(s)',
            'g01_paterno' => 'Apellido parterno',
            'g01_materno' => 'Apellido materno',
            'g01_domicilio' => 'Domicilio',
            'g01_telefono' => 'Telefono',
            'g01_created' => 'Fecha alta',
            'user_id' => 'Usuario alta',
            'g01_status' => 'Status'
        ];
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
    public function getG02Clientes()
    {
        return $this->hasMany(G02Clientes::className(), ['g01_id' => 'g01_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getG04Pagos()
    {
        return $this->hasMany(G04Pagos::className(), ['g01_id' => 'g01_id']);
    }

    /*
        Clientes -> index
        Clientes -> view
        Pagos -> view
    */
    public static function getNamePromotor($g01_id){
        $model = Promotores::findOne($g01_id);
        return $model->g01_nombre . " " . $model->g01_paterno. " " . $model->g01_materno;
    }

    /*
        Clientes -> form
        Clientes -> index
        Pagos -> form
    */
    public static function getListPromotores(){
        return ArrayHelper::map(Promotores::find()->where(['g01_status' => Yii::$app->params['value_active']])->all(), 'g01_id', function($model) {
                return $model['g01_nombre'] . ' ' . $model['g01_paterno'] . ' ' . $model['g01_materno'];
            }
        );
    }
}
