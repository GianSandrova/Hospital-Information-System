<?php

use app\models\Resep;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ResepSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Reseps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resep-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Resep', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'faskes_id',
            'rekam_medis_id',
            'kode_booking',
            'nama_obat',
            //'signa',
            //'jumlah',
            //'jumlah_diminum',
            //'frekuensi',
            //'terakhir_minum',
            //'minum_berikutnya',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Resep $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
