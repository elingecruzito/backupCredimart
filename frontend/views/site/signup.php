<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
//use yii\bootstrap\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\User;

$this->title = Yii::$app->params['txtTitleCrearUsuarios'];
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['/user/index']];
$this->params['breadcrumbs'][] = $title;
?>
<div class="site-signup">

    <?php $form = ActiveForm::begin([
        'id' => 'form-signup',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 10,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>


        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?php //echo $form->field($model, 'email'); ?>

        <?= $isNewRecord ? $form->field($model, 'password')->passwordInput() : '' ?>

        <?= $form->field($model, 'type')->widget(Select2::classname(), [
                'data' => User::getListTipoUsuarios(),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); 
        ?>

        <div  class="pull-right">
            <?= Html::submitButton(Yii::$app->params['btnGuardarUser'] , ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
