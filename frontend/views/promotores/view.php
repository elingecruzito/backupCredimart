<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

use common\models\Utilerias;
use common\models\User;
use kartik\date\DatePicker;
use yii\helpers\Url;
use common\models\Clientes;
use kartik\select2\Select2;

//use kartik\grid\GridView;
//use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Promotores */

$this->title = Yii::$app->params['txtTitleViewPromotor'] . $model->g01_nombre . ' ' . $model->g01_paterno . ' ' . $model->g01_materno;
$this->params['breadcrumbs'][] = ['label' => 'Promotores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="promotores-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'g01_id',
            'g01_nombre:ntext',
            'g01_paterno:ntext',
            'g01_materno:ntext',
            'g01_domicilio:ntext',
            'g01_telefono',
            [
                'format' => 'raw',
                'attribute' => 'g01_created',
                'value' => function($model){
                    return Utilerias::convertTimeStampToFecha($model->g01_created);
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'user_id',
                'value' => function($model){
                    return User::getNameUser($model->user_id);
                },
            ],
        ],
    ]) ?>

    <h4><?= Html::encode(Yii::$app->params['txtTitleIndexClientes']) ?></h4>

    <div class="col-md-12">
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'options' => [
                'class' => 'table-responsive',
            ],
            'dataProvider' => $dataProviderClientes,
            'filterModel' => $searchModelClientes,
            'summary'=> "",
            'rowOptions'=>function($model){
                if($model->g02_tipo == Clientes::TIPO_MOROSO){
                    return ['class' => 'danger'];
                }else{
                    return ['class' => 'success'];
                }
            },
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                //'g02_id',
                'g02_nombre:ntext',
                'g02_paterno:ntext',
                'g02_materno:ntext',
                'g02_domicilio:ntext',
                'g02_telefono',
                [
                    'format' => 'raw',
                    'attribute' => 'g02_fecha_solicitud',
                    'value' => function($model){
                        return Utilerias::invertirFecha($model->g02_fecha_solicitud);
                    },
                    'filter' => DatePicker::widget([
                        'model'=>$searchModelClientes,
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
                        'model' => $searchModelClientes,
                        'attribute' => 'g02_tipo',
                        'data' => Clientes::getTiposClientes(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => '',
                        ]
                    ]),
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons'  => [
                        'view' => function ($url, $model) {

                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                            ];
                            $url = Url::to(['clientes/view', 'id' => $model->g02_id]);
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);

                            return Html::a($icon, $url, $options);
                        },
                        'update' => function ($url, $model) {
                            $options = [
                                'class' => "btn btn-sm btn-primary", 
                            ];
                            $url = Url::to(['clientes/update', 'id' => $model->g02_id]);
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);

                            return Html::a($icon, $url, $options);
                        },
                        'delete' => function ($url, $model) {

                           $options = [
                                'class' => "btn btn-sm btn-primary", 
                                'data-confirm' => Yii::t('yii', Yii::$app->params['msjDelete']),
                                'data-method' => 'post',
                            ];

                            $url = Url::to(['clientes/deletefrompromotor', 'id' => $model->g02_id]);
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);

                            return Html::a($icon, $url, $options);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>

</div>
