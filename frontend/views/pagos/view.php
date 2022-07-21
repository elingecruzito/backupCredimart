<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\models\Clientes;
use common\models\Promotores;
use common\models\Utilerias;

/* @var $this yii\web\View */
/* @var $model app\models\Pagos */

$this->title = Yii::$app->params['txtTitleViewPagos'] . Clientes::getCliente($model->g02_id);
$this->params['breadcrumbs'][] = ['label' => 'Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pagos-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'raw', 
                'attribute' => 'g01_id',
                'value' => function($model){
                    return Promotores::getNamePromotor($model->g01_id);
                }
            ],
            [
                'format' => 'raw',
                'attribute' => 'g02_id',
                'value' => function($model){
                    return Clientes::getCliente($model->g02_id);
                },
            ],
            'g03_id',
            [
                'format' => 'raw',
                'attribute' => 'g04_cantidad',
                'value' => function($model){
                    return Utilerias::formatMoney($model->g04_cantidad);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'g04_fecha',
                'value' => function($model){
                    return Utilerias::invertirFecha($model->g04_fecha);
                },
            ],
            'g04_semana',
        ],
    ]) ?>

</div>
