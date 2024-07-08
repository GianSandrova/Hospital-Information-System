<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PerjanjianSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="perjanjian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'rekam_medis_id') ?>

    <?= $form->field($model, 'faskes_id') ?>

    <?= $form->field($model, 'kode_booking') ?>

    <?= $form->field($model, 'poli') ?>

    <?php // echo $form->field($model, 'dokter') ?>

    <?php // echo $form->field($model, 'jadwal') ?>

    <?php // echo $form->field($model, 'waktu_perjanjian') ?>

    <?php // echo $form->field($model, 'no_antrian') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'is_delete')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
