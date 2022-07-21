<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pagos */

$this->title = Yii::$app->params['txtTitleModificarPagos'];
$this->params['breadcrumbs'][] = ['label' => 'Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->params['txtTitleViewPagos'] . $model->g04_id, 'url' => ['view', 'id' => $model->g04_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagos-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
