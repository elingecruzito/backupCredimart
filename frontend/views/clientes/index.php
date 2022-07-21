<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Utilerias;
use common\models\Promotores;
use kartik\date\DatePicker;
use common\models\Clientes;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ClientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['txtTitleIndexClientes'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clientes-index">

     <div class="box-tools pull-right">
        <?= Html::a(Yii::$app->params['btnAñadirCliente'], ['create'], ['class' => 'btn btn-primary']) ?>
    </div><br/>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="col-md-12">
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'options' => [
                'class' => 'table-responsive',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions'=>function($model){
                if($model->g02_tipo == Clientes::TIPO_MOROSO){
                    return ['class' => 'danger'];
                }else{
                    return ['class' => 'success'];
                }
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],


                //'g02_id',
                'g02_nombre:ntext',
                'g02_paterno:ntext',
                'g02_materno:ntext',
                //'g02_domicilio:ntext',
                //'g02_telefono',
                [
                    'format' => 'raw',
                    'attribute' => 'g01_id',
                    'value' => function($model){
                        return Promotores::getNamePromotor($model->g01_id);
                    },
                    //'filter' => Promotores::getListPromotores(),
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
                    'attribute' => 'g02_fecha_solicitud',
                    'value' => function($model){
                        return Utilerias::invertirFecha($model->g02_fecha_solicitud);
                    },
                    'filter' => DatePicker::widget([
                        'model'=>$searchModel,
                        'language' => 'es',
                        'attribute'=>'g02_fecha_solicitud',
                        'pluginOptions' => [
                            'format' => 'dd/mm/yyyy',
                        ]
                    ]),
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

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
