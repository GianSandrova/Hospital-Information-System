<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Faskes $model */

$this->title = 'Update Faskes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faskes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="faskes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
