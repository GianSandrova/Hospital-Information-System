<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Faskes $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faskes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="faskes-view">

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
            'client_id',
            'nama_faskes',
            'alamat',
            'deskripsi:ntext',
            'logo',
            'is_aktif:boolean',
            'is_bridging:boolean',
            'ip_address',
            'user_api',
            'password_api',
            'longtitud',
            'latitude',
        ],
    ]) ?>

</div>
