<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Promotores */

$this->title = Yii::$app->params['txtTitleCrearPromotores'];
$this->params['breadcrumbs'][] = ['label' => 'Promotores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotores-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
