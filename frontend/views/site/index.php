<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Prestamos;
use common\models\Utilerias;
use common\models\Clientes;
use common\models\Promotores;
use common\models\Perfiles;
use common\models\User;

use kartik\select2\Select2;
use kartik\date\DatePicker;

$this->title = Yii::$app->name;
?>
<div class="site-index">


<?php 
if(!Yii::$app->user->isGuest){
    if (Perfiles::findModel(Yii::$app->user->id)->g05_tipo == User::TIPO_ADMINISTRADOR) {

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions'=>function($model){
                    if($model->g03_estado == Prestamos::ESTADO_PENDIENTE){
                        return ['class' => 'warning'];
                    }else if($model->g03_estado == Prestamos::ESTADO_LIQUIDADO){
                        return ['class' => 'success'];
                    }else{
                        return ['class' => 'danger'];
                    }
                },
            'columns' => [
                'g03_id',
                [
                    'format' => 'raw',
                    'attribute' => 'g01_id',
                    'value' => function($model){
                        return $model->g01_nombre;
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'g01_id',
                        'data' => Promotores::getListPromotores(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        //'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g02_id',
                    'value' => function($model){
                        return $model->g02_nombre;
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'g02_id',
                        'data' => Clientes::getListClientes(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        //'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g03_fecha',
                    'value' => function($model){
                        return Utilerias::invertirFecha($model->g03_fecha);
                    },
                    'filter' => DatePicker::widget([
                        'model'=>$searchModel,
                        'language' => 'es',
                        'attribute'=>'g03_fecha',
                        'pluginOptions' => [
                            'format' => 'dd/mm/yyyy',
                        ]
                    ]),
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g03_total',
                    'value' => function($model){
                        return Utilerias::formatMoney($model->g03_total);
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g04_cantidad',
                    'value' => function($model){
                        return Utilerias::formatMoney($model->g04_cantidad);
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g02_tipo',
                    'value' => function($model){
                        return Clientes::getTipoCliente($model->g02_tipo);
                    },
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'g02_tipo',
                        'data' => Clientes::getTiposClientes(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],
            ],
        ]); 
    }else{
    ?>
        <div align="center">
            <?= Html::img(Yii::$app->homeUrl . 'img/logo.png'); ?>
        </div>
    <?php
    }
}
?>

</div>
