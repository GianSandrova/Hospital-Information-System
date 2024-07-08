<?php

use app\models\Endpoint;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EndpointSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Endpoints';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="endpoint-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Endpoint', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'faskes_id',
            'name',
            'url:url',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Endpoint $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
