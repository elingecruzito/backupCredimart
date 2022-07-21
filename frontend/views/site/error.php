<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="content-error">
    
    <div align="center">

        <?= Html::img(Yii::$app->request->baseUrl . '/img/face-error.png', ["width"=>"200px"]); ?>

        <h1 class="code-error"><?= $name ?></h1>

        <p class="message-error"><?= nl2br(Html::encode($message)) ?></p>

    </div>

    <div align="center">
        
        <?= Html::a(Yii::$app->params['backDashboard'], ['/'], ['class' => 'btn btn-primary']) ?>

    </div>

</div>