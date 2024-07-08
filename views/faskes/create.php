<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Faskes $model */

$this->title = 'Create Faskes';
$this->params['breadcrumbs'][] = ['label' => 'Faskes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faskes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
