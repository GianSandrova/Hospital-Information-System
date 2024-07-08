<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Perjanjian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="perjanjian-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rekam_medis_id')->textInput() ?>

    <?= $form->field($model, 'faskes_id')->textInput() ?>

    <?= $form->field($model, 'kode_booking')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poli')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dokter')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jadwal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'waktu_perjanjian')->textInput() ?>

    <?= $form->field($model, 'no_antrian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_delete')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
