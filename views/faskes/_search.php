<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\FaskesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faskes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'nama_faskes') ?>

    <?= $form->field($model, 'alamat') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'is_aktif')->checkbox() ?>

    <?php // echo $form->field($model, 'is_bridging')->checkbox() ?>

    <?php // echo $form->field($model, 'ip_address') ?>

    <?php // echo $form->field($model, 'user_api') ?>

    <?php // echo $form->field($model, 'password_api') ?>

    <?php // echo $form->field($model, 'longtitud') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
