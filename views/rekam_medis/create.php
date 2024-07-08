<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Rekam_medis $model */

$this->title = 'Create Rekam Medis';
$this->params['breadcrumbs'][] = ['label' => 'Rekam Medis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekam-medis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
