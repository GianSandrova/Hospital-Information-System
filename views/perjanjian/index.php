<?php

use app\models\Perjanjian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PerjanjianSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Perjanjians';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perjanjian-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Perjanjian', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'rekam_medis_id',
            'faskes_id',
            'kode_booking',
            'poli',
            //'dokter',
            //'jadwal',
            //'waktu_perjanjian',
            //'no_antrian',
            //'status',
            //'is_delete:boolean',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Perjanjian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
