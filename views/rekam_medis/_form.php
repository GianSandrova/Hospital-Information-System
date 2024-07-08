<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Rekam_medis $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rekam-medis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'personal_id')->textInput() ?>

    <?= $form->field($model, 'faskes_id')->textInput() ?>

    <?= $form->field($model, 'no_rm')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
