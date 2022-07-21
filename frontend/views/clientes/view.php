<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

use common\models\Utilerias;
use common\models\Promotores;
use common\models\Clientes;
use yii\helpers\Url;
use common\models\Prestamos;
use common\models\Pagos;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */

$this->title = Yii::$app->params['txtTitleViewCliente'] . $model->g02_nombre . ' ' . $model->g02_paterno . ' ' . $model->g02_materno;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="clientes-view">

    <div class="col-md-12">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'g02_id',
                    'g02_nombre:ntext',
                    'g02_paterno:ntext',
                    'g02_materno:ntext',
                    [
                        'format' => 'raw',
                        'attribute' => 'file',
                        'value' => function($model){
                            return Html::img($model->file, ['width' => '100px']);
                        }
                    ],
                    'g02_domicilio:ntext',
                    'g02_telefono',
                    [
                        'format' => 'raw',
                        'attribute' => 'g01_id',
                        'value' => function($model){
                            return Promotores::getNamePromotor($model->g01_id);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'g02_fecha_solicitud',
                        'value' => function($model){
                            return Utilerias::invertirFecha($model->g02_fecha_solicitud);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'g02_tipo',
                        'value' => function($model){
                            return Clientes::getTipoCliente($model->g02_tipo);
                        },
                    ],
                ],
            ]) ?>

        </div>
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'g02_nombre_aval:ntext',
                    'g02_paterno_aval:ntext',
                    'g02_materno_aval:ntext',
                    'g02_domicilio_aval:ntext',
                    'g02_telefono_aval',
                ],
            ]) ?>
            <br/><br/>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'format' => 'raw',
                        'attribute' => 'g02_maximo',
                        'value' => function($model){
                            return Utilerias::formatMoney($model->g02_maximo);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'label' => 'Total prestamos',
                        'value' => function($model){
                            return Utilerias::formatMoney(Prestamos::getTotalPrestamos($model->g02_id));
                        },
                    ],
                    [
                        'format' => 'raw',
                        'label' => 'Total pagos',
                        'value' => function($model){
                            return Utilerias::formatMoney(Pagos::getTotalPagosPrestamos($model->g02_id));
                        },
                    ],
                    [
                        'format' => 'raw',
                        'label' => 'Adeudo',
                        'value' => function($model){
                            $totalPrestamos = Prestamos::getTotalPrestamos($model->g02_id);
                            $totalPagos = Pagos::getTotalPagosPrestamos($model->g02_id);
                            return Utilerias::formatMoney($totalPrestamos - $totalPagos);
                        },
                    ],
                ],
            ]) ?>

        </div>
    </div>

    <div class="col-md-12">
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'options' => [
                'class' => 'table-responsive',
            ],
            'dataProvider' => $dataProviderPrestamos,
            'filterModel' => $searchModelPrestamos,
            'summary'=> "",
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
                        'model'=>$searchModelPrestamos,
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
                        'model' => $searchModelPrestamos,
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
                        'view' => function ($url, $model) {
                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                            ];
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                            $url = Url::to(['prestamos/view', 'id' => $model->g03_id]);

                            return Html::a($icon, $url, $options);
                        },
                        'update' => function ($url, $model) {

                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                            ];
                            $url = Url::to(['prestamos/update', 'id' => $model->g03_id]);

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
                            $url = Url::to(['prestamos/deletefromcliente', 'id' => $model->g03_id]);

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
