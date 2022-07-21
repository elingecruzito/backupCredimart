<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\models\Clientes;
use common\models\Prestamos;
use common\models\Pagos;
use common\models\Utilerias;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Prestamos */

$this->title = Yii::$app->params['txtTitleViewPrestamos'] . Clientes::getCliente($model->g02_id);
$this->params['breadcrumbs'][] = ['label' => 'Prestamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


//$this->registerCssFile(Yii::$app->request->baseUrl.'/js/fullcalendar/dist/core/main.css' , ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
//$this->registerCssFile(Yii::$app->request->baseUrl.'/js/fullcalendar/dist/daygrid/main.css' , ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);
//$this->registerJsFile(Yii::$app->request->baseUrl.'/js/fullcalendar/dist/core/main.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(Yii::$app->request->baseUrl.'/js/fullcalendar/dist/interaction/main.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);
//$this->registerJsFile(Yii::$app->request->baseUrl.'/js/fullcalendar/dist/daygrid/main.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);

//$this->registerJsFile(Yii::$app->request->baseUrl.'/js/calendario.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);
?>

<div class="prestamos-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'g03_id',
            [
                'format' => 'raw',
                'attribute' => 'g02_id',
                'value' => function($model){
                    return Clientes::getCliente($model->g02_id);
                },
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
                'attribute' => 'g03_fecha',
                'value' => function($model){
                    return Utilerias::invertirFecha($model->g03_fecha);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'g03_estado',
                'value' => function($model){
                    return Prestamos::getEstadoPrestamo($model->g03_estado);
                },
            ],
            [
                'format' => 'raw',
                'label' => 'Fecha tentativa de corte',
                'value' => function($model){
                    return Utilerias::getCorteSemanas($model->g03_fecha);
                }
            ],            
        ],
    ]) ?>

    <div class="col-md-12">
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'options' => [
                'class' => 'table-responsive',
            ],
            'rowOptions' => function($model){
                $modelPrestamo = Prestamos::findModel($model->g03_id);
                if(Utilerias::getValidPayDay($model->g04_fecha) && $modelPrestamo->g03_abono == $model->g04_cantidad){
                    return ['class' => 'success'];
                }
                return ['class' => 'warning'];
            },
            'dataProvider' => $dataProviderPagos,
            'filterModel' => $searchModelPagos,
            'summary'=> "",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
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
                    'filter' => DatePicker::widget([
                        'model'=>$searchModelPagos,
                        'attribute'=>'g04_fecha',
                        'pluginOptions' => [
                            'format' => 'dd/mm/yyyy',
                        ]
                    ]),
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g04_semana',
                    'value' => function($model){
                        return Pagos::getSemana($model->g04_semana);
                    },
                    //'filter' => Pagos::getListSemanas(),
                    'filter' => Select2::widget([
                        'model' => $searchModelPagos,
                        'attribute' => 'g04_semana',
                        'data' => Pagos::getListSemanas(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        //'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],
                [
                    'class' => 'yii\grid\ActionColumn', 
                    'template' => '{view}{delete}',
                    'buttons'  => [
                        'view' => function ($url, $model) {
                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                            ];
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                            $url = Url::to(['pagos/view', 'id' => $model->g04_id]);

                            return Html::a($icon, $url, $options);
                        },
                        'delete' => function ($url, $model) {

                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'data-confirm' => Yii::t('yii', Yii::$app->params['msjDelete']),
                                'data-method' => 'post',
                            ];

                            $url = Url::to(['pagos/deletefromprestamo', 'id' => $model->g04_id]);
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);

                            if(Prestamos::findModel($model->g03_id)->g03_estado == Prestamos::ESTADO_LIQUIDADO){
                                $options = [
                                    'class' => "btn btn-sm btn-primary", 
                                    'disabled' => 'disabled',
                                ];
                                $url = "#";
                            }

                            return Html::a($icon, $url, $options);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
