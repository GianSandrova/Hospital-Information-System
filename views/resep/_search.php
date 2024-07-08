<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ResepSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="resep-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'faskes_id') ?>

    <?= $form->field($model, 'rekam_medis_id') ?>

    <?= $form->field($model, 'kode_booking') ?>

    <?= $form->field($model, 'nama_obat') ?>

    <?php // echo $form->field($model, 'signa') ?>

    <?php // echo $form->field($model, 'jumlah') ?>

    <?php // echo $form->field($model, 'jumlah_diminum') ?>

    <?php // echo $form->field($model, 'frekuensi') ?>

    <?php // echo $form->field($model, 'terakhir_minum') ?>

    <?php // echo $form->field($model, 'minum_berikutnya') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
