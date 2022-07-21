<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "v02_reporte_clientes".
 *
 * @property string $g01_nombre
 * @property int $g02_id ID
 * @property string $g02_nombre
 * @property int $g02_tipo Tipo cliente
 */
class ReporteClientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v02_reporte_clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g01_nombre', 'g02_nombre'], 'required'],
            [['g01_nombre', 'g02_nombre'], 'string'],
            [['g02_id', 'g02_tipo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g01_nombre' => 'G01 Nombre',
            'g02_id' => 'ID',
            'g02_nombre' => 'G02 Nombre',
            'g02_tipo' => 'Tipo cliente',
        ];
    }
}
