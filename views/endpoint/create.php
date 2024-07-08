<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Endpoint $model */

$this->title = 'Create Endpoint';
$this->params['breadcrumbs'][] = ['label' => 'Endpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="endpoint-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
