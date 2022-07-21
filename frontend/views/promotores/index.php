<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PromotoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['txtTitleIndexPromotores'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotores-index">
    <div class="box-tools pull-right">
        <?= Html::a(Yii::$app->params['btnAñadirPromotor'], ['create'], ['class' => 'btn btn-primary']) ?>
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
            'showFooter' => true,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'g01_id',
                'g01_nombre:ntext',
                'g01_paterno:ntext',
                'g01_materno:ntext',
                //'g01_domicilio:ntext',
                'g01_telefono',
                //'g01_created',
                //'user_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    


</div>
