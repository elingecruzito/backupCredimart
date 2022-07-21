<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Prestamos;
use common\models\Clientes;
use common\models\Pagos;
use kartik\date\DatePicker;
use common\models\Utilerias;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PrestamosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['txtTitleIndexPrestamos'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prestamos-index">

    <div class="box-tools pull-right">
        <?= Html::a(Yii::$app->params['btnAñadirPrestamos'], ['create'], ['class' => 'btn btn-primary']) ?>
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
                if($model->g03_estado == Prestamos::ESTADO_PENDIENTE){
                    return ['class' => 'warning'];
                }else if($model->g03_estado == Prestamos::ESTADO_LIQUIDADO){
                    return ['class' => 'success'];
                }else{
                    return ['class' => 'danger'];
                }
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'g03_id',
                [
                    'format' => 'raw',
                    'attribute' => 'g02_id',
                    'value' => function($model){
                        return Clientes::getCliente($model->g02_id);
                    },
                    //'filter' => Clientes::getListClientes(),
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
                    'attribute' => 'g03_monto',
                    'value' => function($model){
                        return Utilerias::formatMoney($model->g03_monto);
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g03_abono',
                    'value' => function($model){
                        return Utilerias::formatMoney($model->g03_abono);
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g03_total',
                    'value' => function($model){
                        return Utilerias::formatMoney($model->g03_total);
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'totalPagos',
                    'value' => function($model){
                        return Utilerias::formatMoney(Pagos::getTotalPagos($model->g03_id));
                    }
                ],
                //'g03_fecha',
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
                    'attribute' => 'g03_estado',
                    'value' => function($model){
                        return Prestamos::getEstadoPrestamo($model->g03_estado);
                    },
                    //'filter' => Prestamos::getListEstadosPrestamos(),
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'g03_estado',
                        'data' => Prestamos::getListEstadosPrestamos(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],
                //'g03_created',
                //'user_id',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons'  => [
                        'update' => function ($url, $model) {

                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                            ];

                            if($model->g03_estado == Prestamos::ESTADO_LIQUIDADO){
                                $options = [
                                    'class' => "btn btn-sm btn-primary", 
                                    'disabled' => 'disabled',
                                ];
                                $url = "#";
                            }

                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                            return Html::a($icon, $url, $options);

                        },
                        'delete' => function ($url, $model) {

                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'data-confirm' => Yii::t('yii', Yii::$app->params['msjDelete']),
                                'data-method' => 'post',
                            ];

                            if($model->g03_estado == Prestamos::ESTADO_LIQUIDADO){
                                $options = [
                                    'class' => "btn btn-sm btn-primary", 
                                    'disabled' => 'disabled',
                                ];
                                $url = "#";
                            }

                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                            return Html::a($icon, $url, $options);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>

</div>
