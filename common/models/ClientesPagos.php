<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "v01_clientes_pagos".
 *
 * @property int $g04_id ID
 * @property int $g01_id ID
 * @property int $g02_id ID
 * @property int $g03_id ID
 * @property int $g04_cantidad Monto
 * @property string $g04_fecha Fecha pago
 * @property int $g04_semana Semana
 * @property int $g04_status Status
 * @property int $user_id Usuario alta
 */
class ClientesPagos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v01_clientes_pagos';
    }

    public static function primaryKey(){
        return ["g04_id"];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g04_id', 'g01_id', 'g02_id', 'g03_id', 'g04_cantidad', 'g04_semana', 'g04_status', 'user_id'], 'integer'],
            [['g04_cantidad', 'g04_fecha', 'g04_semana', 'user_id'], 'required'],
            [['g04_fecha'], 'safe'],
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
            'g03_id' => 'Prestamo',
            'g04_cantidad' => 'Monto',
            'g04_fecha' => 'Fecha pago',
            'g04_semana' => 'Semana',
            'g04_status' => 'Status',
            'user_id' => 'Usuario alta',
        ];
    }
}
