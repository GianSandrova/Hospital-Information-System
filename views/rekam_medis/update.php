<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Rekam_medis $model */

$this->title = 'Update Rekam Medis: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rekam Medis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rekam-medis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
