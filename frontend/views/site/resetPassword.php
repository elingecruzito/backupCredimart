<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = Yii::$app->params['txtTitleChangePassword'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <p><?= Yii::$app->params['msjChangePasswordForm'] ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'reset-password-form',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 10,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>
        <div class="col-lg-12">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">        
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
            </div>
        </div>
         <div class="pull-right">
            <?= Html::a('Cancelar', ['/'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::$app->params['btnGuardarUser'] , ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
