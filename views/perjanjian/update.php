<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Perjanjian $model */

$this->title = 'Update Perjanjian: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Perjanjians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="perjanjian-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
