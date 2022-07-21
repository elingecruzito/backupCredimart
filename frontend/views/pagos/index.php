<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Promotores;
use common\models\Clientes;
use common\models\Pagos;
use common\models\Prestamos;
use common\models\Utilerias;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PagosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['txtTitleIndexPagos'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagos-index">

    <div class="box-tools pull-right">
        <?= Html::a(Yii::$app->params['btnAñadirPagos'], ['create'], ['class' => 'btn btn-primary']) ?>
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
            'rowOptions' => function($model){
                $modelPrestamo = Prestamos::findModel($model->g03_id);
                if(Utilerias::getValidPayDay($model->g04_fecha) && $modelPrestamo->g03_abono == $model->g04_cantidad){
                    return ['class' => 'success'];
                }
                return ['class' => 'warning'];
            },
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
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
                    'attribute' => 'g04_cantidad',
                    'value' => function($model){
                        return Utilerias::formatMoney($model->g04_cantidad);
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'g04_fecha',
                    'value' => function($model){
                        return Utilerias::getDayOfWeek($model->g04_fecha) . ' ' . Utilerias::invertirFecha($model->g04_fecha) ;
                    },
                    'filter' => DatePicker::widget([
                        'model'=>$searchModel,
                        'language' => 'es',
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
                        'model' => $searchModel,
                        'attribute' => 'g04_semana',
                        'data' => Pagos::getListSemanas(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        //'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],
                'g03_id',                
                //'g04_created',
                //'user_id',

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

                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                            $url = Url::to(['pagos/delete', 'id' => $model->g04_id]);

                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'data-confirm' => Yii::t('yii', Yii::$app->params['msjDelete']),
                                'data-method' => 'post',
                            ];


                            if(Prestamos::findModel($model->g03_id)->g03_estado != Prestamos::ESTADO_PENDIENTE){
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
