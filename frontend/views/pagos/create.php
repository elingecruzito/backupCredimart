<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pagos */

$this->title = Yii::$app->params['txtTitleCrearPagos'];
$this->params['breadcrumbs'][] = ['label' => 'Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagos-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
