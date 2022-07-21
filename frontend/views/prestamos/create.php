<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prestamos */

$this->title = Yii::$app->params['txtTitleCrearPrestamos'];
$this->params['breadcrumbs'][] = ['label' => 'Prestamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prestamos-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
