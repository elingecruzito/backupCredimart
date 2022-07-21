<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "c01_movimientos".
 *
 * @property int $c01_id ID
 * @property int $c01_tipo Tipo movimiento
 * @property int $c01_id_tabla Tabla
 * @property int $c01_tabla ID tabla
 * @property int $user_id Usuario
 * @property string $c01_date Fecha
 */
class Movimientos extends \yii\db\ActiveRecord
{
    const MOVIMIENTO_INSERT = 1;
    const MOVIMIENTO_UPDATE = 2;
    const MOVIMIENTO_DELETE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c01_movimientos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c01_tipo', 'c01_id_tabla', 'c01_tabla', 'user_id'], 'required'],
            [['c01_tipo', 'c01_id_tabla', 'user_id'], 'integer'],
            [['c01_tabla','c01_old_row', 'c01_new_row'] , 'string'],
            [['c01_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'c01_id' => 'ID',
            'c01_tipo' => 'Tipo movimiento',
            'c01_id_tabla' => 'ID tabla',
            'c01_tabla' => 'Tabla',
            'user_id' => 'Usuario',
            'c01_date' => 'Fecha',
            'c01_old_row' => 'Anterior registro',
            'c01_new_row' => 'Nuevo registro',
        ];
    }

    public static function getListMovimientos(){
        return [
            Movimientos::MOVIMIENTO_INSERT => 'NUEVO',
            Movimientos::MOVIMIENTO_UPDATE => 'MODIFICACION',
            Movimientos::MOVIMIENTO_DELETE => 'ELIMINACION'
        ];
    }

    public static function getMovimiento($c01_tipo){
        return Movimientos::getListMovimientos()[$c01_tipo];
    }

    public static function getListTablas(){
        return ArrayHelper::map(Movimientos::find()->groupBy('c01_tabla')->all(), 'c01_tabla', function($model) {
                $array = explode("_", $model->c01_tabla);
                return strtoupper($array[1]);
            }
        );
    }

    public static function executeQueryMovimientos($query){
        $connection = Yii::$app->db;
        echo $query;
        $command = $connection->createCommand($query);
        return $command->query();
        
    }
}
