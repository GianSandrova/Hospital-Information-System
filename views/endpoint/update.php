<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Endpoint $model */

$this->title = 'Update Endpoint: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Endpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="endpoint-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
