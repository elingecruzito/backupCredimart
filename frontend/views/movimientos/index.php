<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Movimientos;
use common\models\User;
use common\models\Utilerias;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MovimientosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['txtTitleIndexMovimientos'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimientos-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                $tipo = '';
                switch ($model->c01_tipo) {
                    case Movimientos::MOVIMIENTO_INSERT: $tipo = 'success'; break;
                    case Movimientos::MOVIMIENTO_UPDATE: $tipo = 'warning'; break;
                    case Movimientos::MOVIMIENTO_DELETE: $tipo = 'danger'; break;
                }

                return ['class' => $tipo];
            },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'c01_id',
            [
                'format' => 'raw',
                'attribute' => 'c01_tipo',
                'value' => function($model){
                    return Movimientos::getMovimiento($model->c01_tipo);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'c01_tipo',
                    'data' => Movimientos::getListMovimientos(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => '',
                    ]
                ]),
            ],
            'c01_id_tabla',
            //'c01_tabla:ntext',
            [
                'format' => 'raw',
                'attribute' => 'c01_tabla',
                'value' => function($model){
                    $array = explode("_", $model->c01_tabla);
                    return strtoupper($array[1]);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'c01_tabla',
                    'data' => Movimientos::getListTablas(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => '',
                    ]
                ]),
            ],
            //'user_id',
            [
                'format' => 'raw',
                'attribute' => 'user_id',
                'value' => function($model){
                    return User::getNameUser($model->user_id);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'user_id',
                    'data' => User::getUsers(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => '',
                    ]
                ]),
            ],
            //'c01_date',
            [
                'format' => 'raw',
                'attribute' => 'c01_date',
                'value' => function($model){
                    return Utilerias::convertTimeStampToFecha($model->c01_date);
                },
                'filter' => DatePicker::widget([
                    'model'=>$searchModel,
                    'language' => 'es',
                    'attribute'=>'c01_date',
                    'pluginOptions' => [
                        'format' => 'dd/mm/yyyy',
                    ]
                ]),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{last}{new}',
                'buttons'  => [
                    'view' => function($url, $model){

                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                        $url = Url::to(['movimientos/view', 'id' => $model->c01_id]);
                        $options = ['class' => "btn btn-sm btn-primary"];

                        if($model->c01_tipo == Movimientos::MOVIMIENTO_INSERT){
                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'disabled' => 'disabled',
                            ];
                            $url = "#";
                        }

                        return Html::a($icon, $url, $options);
                    },
                    'last' => function ($url, $model) {
                        $options = [
                            'class' => "btn btn-sm btn-primary", 
                            'data-confirm' => Yii::t('yii', Yii::$app->params['msjConfirmLastMovimiento']),
                            'data-method' => 'post',    
                        ];
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-repeat"]);
                        $url = Url::to(['movimientos/last-row', 'id' => $model->c01_id]);

                        if($model->c01_tipo == Movimientos::MOVIMIENTO_INSERT){
                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'disabled' => 'disabled',
                            ];
                            $url = "#";
                        }

                        return Html::a($icon, $url, $options);
                    },
                    'new' => function ($url, $model) {
                        
                        $options = [
                            'class' => "btn btn-sm btn-primary",
                            'data-confirm' => Yii::t('yii', Yii::$app->params['msjConfirmNewMovimiento']),
                            'data-method' => 'post', 
                        ];
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-refresh"]);
                        $url = Url::to(['movimientos/new-row', 'id' => $model->c01_id]);

                        if($model->c01_tipo == Movimientos::MOVIMIENTO_INSERT){
                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'disabled' => 'disabled',
                            ];
                            $url = "#";
                        }

                        return Html::a($icon, $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
