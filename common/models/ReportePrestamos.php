<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "v03_reporte_prestamos".
 *
 * @property int $g03_id ID
 * @property string $g01_nombre
 * @property string $g02_nombre
 * @property int $g03_estado Estado
 * @property string $g03_fecha Fecha prestamo
 * @property int $g03_total Total a pagar
 * @property string $g04_cantidad
 * @property int $g02_tipo Tipo cliente
 */
class ReportePrestamos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v03_reporte_prestamos';
    }

    /**
     * @inheritdoc$primaryKey
     */
    public static function primaryKey(){
        return ["g03_id"];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['g03_id', 'g03_estado', 'g03_total', 'g02_tipo', 'g01_id', 'g02_id'], 'integer'],
            [['g01_nombre', 'g02_nombre', 'g03_fecha', 'g03_total', 'g01_id', 'g02_id'], 'required'],
            [['g01_nombre', 'g02_nombre'], 'string'],
            [['g03_fecha'], 'safe'],
            [['g04_cantidad'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'g03_id' => 'Prestamo',
            'g01_id' => 'Promotor',
            'g01_nombre' => 'Promotor',
            'g02_id' => 'Cliente',
            'g02_nombre' => 'Cliente',
            'g03_estado' => 'Estado prestamo',
            'g03_fecha' => 'Fecha prestamo',
            'g03_total' => 'Total a pagar',
            'g04_cantidad' => 'Cantidad pagado',
            'g02_tipo' => 'Tipo cliente',
        ];
    }
}
