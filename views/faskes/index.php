<?php

use app\models\Faskes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\FaskesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Faskes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faskes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Faskes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'client_id',
            'nama_faskes',
            'alamat',
            'deskripsi:ntext',
            //'logo',
            //'is_aktif:boolean',
            //'is_bridging:boolean',
            //'ip_address',
            //'user_api',
            //'password_api',
            //'longtitud',
            //'latitude',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Faskes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
