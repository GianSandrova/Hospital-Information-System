<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Perjanjian $model */

$this->title = 'Create Perjanjian';
$this->params['breadcrumbs'][] = ['label' => 'Perjanjians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perjanjian-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
