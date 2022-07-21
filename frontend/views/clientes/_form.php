<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use common\models\Promotores;
use common\models\Perfiles;
use common\models\User;
use common\models\Clientes;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Clientes */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/clientes-form.js', ['depends'=>[\yii\web\JqueryAsset::className()]]);
?>

<div class="clientes-form">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 10,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
             <?= $form->field($model, 'g02_fecha_solicitud')->widget(DatePicker::classname(), [
                   'language' => 'es',
                   'pluginOptions' => [
                        'format' => 'dd/mm/yyyy',
                    ]
            ]) ?>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-6">
            <?= $form->field($model, 'g01_id')->widget(Select2::classname(), [
                'data' => Promotores::getListPromotores(),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

            <?= $form->field($model, 'g02_nombre')->textInput(['id' => 'g02_nombre']) ?>

            <?= $form->field($model, 'g02_paterno')->textInput(['id' => 'g02_paterno']) ?>

            <?= $form->field($model, 'g02_materno')->textInput(['id' => 'g02_materno']) ?>

            <?= $form->field($model, 'g02_domicilio')->textInput(['id' => 'g02_domicilio']) ?>

            <?= $form->field($model, 'g02_telefono')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '999-999-9999']) ?>

            <?php
                if(Perfiles::findModel(Yii::$app->user->id)->g05_tipo == User::TIPO_ADMINISTRADOR){
                    echo $form->field($model, 'g02_tipo')->widget(Select2::classname(), [
                        'data' => Clientes::getTiposClientes(),
                        'options' => ['placeholder' => ''],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); 
                }
            ?>          

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'g02_nombre_aval')->textInput(['id' => 'g02_nombre_aval']) ?>

            <?= $form->field($model, 'g02_paterno_aval')->textInput(['id' => 'g02_paterno_aval']) ?>

            <?= $form->field($model, 'g02_materno_aval')->textInput(['id' => 'g02_materno_aval']) ?>

            <?= $form->field($model, 'g02_domicilio_aval')->textInput(['id' => 'g02_domicilio_aval']) ?>

            <?= $form->field($model, 'g02_telefono_aval')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '999-999-9999']) ?>

            <?= $form->field($model, 'g02_maximo')->textInput() ?>
        </div>
    </div>

    <div class="col-md-12">

        <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                'pluginOptions' => [

                    'initialPreview'=> !$model->isNewRecord ? [$model->file] : [],
                    'initialPreviewAsData'=> !$model->isNewRecord ? true : false,
                    'initialPreviewConfig' =>  !$model->isNewRecord ? [['caption' => $model->g02_img]] : [],

                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'browseClass' => 'btn btn-primary btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'browseLabel' =>  ''
                ],
                'options' => ['accept' => 'image/*', 'multiple' => false],
            ]);
        ?>

    </div>
    

    <div class="pull-right">
            <?= Html::a('Cancelar', ['/clientes'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(Yii::$app->params['btnGuardarCliente'] , ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
