<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Perjanjian $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Perjanjians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="perjanjian-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'rekam_medis_id',
            'faskes_id',
            'kode_booking',
            'poli',
            'dokter',
            'jadwal',
            'waktu_perjanjian',
            'no_antrian',
            'status',
            'is_delete:boolean',
        ],
    ]) ?>

</div>
