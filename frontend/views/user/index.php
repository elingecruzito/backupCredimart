<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Perfiles;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    
    <div class="box-tools pull-right">
        <?= Html::a(Yii::$app->params['btnAñadirUsuario'], ['create'], ['class' => 'btn btn-primary']) ?>
    </div><br/>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            //'status',
            //'created_at',
            [
                'format' => 'raw',
                'label' => 'Tipo de usuario',
                'value' => function($model){

                    return Perfiles::getTypeUser($model->id);
                }
            ],
            //'updated_at',
            //'verification_token',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
