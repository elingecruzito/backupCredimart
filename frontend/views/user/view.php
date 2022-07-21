<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Perfiles;

use yii\grid\GridView;
use common\models\Movimientos;
use common\models\User;
use frontend\models\Utilerias;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::$app->params['txtTitleViewUsuarios'] . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            //'status',
            [
                'format' => 'raw',
                'label' => 'Tipo de usuario',
                'value' => function($model){

                    return Perfiles::getTypeUser($model->id);
                }
            ],
            [
                'format' => 'raw',
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d/m/y', $model->created_at);
                },
            ],
            //'updated_at',
            //'verification_token',
        ],
    ]) ?>


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
                'template' => '',
            ],
        ],
    ]); ?>

</div>
