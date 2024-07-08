<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Resep $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="resep-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'faskes_id')->textInput() ?>

    <?= $form->field($model, 'rekam_medis_id')->textInput() ?>

    <?= $form->field($model, 'kode_booking')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_obat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'signa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah')->textInput() ?>

    <?= $form->field($model, 'jumlah_diminum')->textInput() ?>

    <?= $form->field($model, 'frekuensi')->textInput() ?>

    <?= $form->field($model, 'terakhir_minum')->textInput() ?>

    <?= $form->field($model, 'minum_berikutnya')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
