<h1>View Faskes</h1>
<?php 
    use yii\helpers\Html;
?>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <td><?= Html::encode($faskes->id) ?></td>
    </tr>
    <tr>
        <th>Nama Faskes</th>
        <td><?= Html::encode($faskes->nama_faskes) ?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td><?= Html::encode($faskes->alamat) ?></td>
    </tr>
    <tr>
        <th>Deskripsi</th>
        <td><?= Html::encode($faskes->deskripsi) ?></td>
    </tr>
    <tr>
        <th>Longtitude</th>
        <td><?= Html::encode($faskes->longtitud) ?></td>
    </tr>
    <tr>
        <th>Latitude</th>
        <td><?= Html::encode($faskes->latitude) ?></td>
    </tr>
</table>

<p>
    <?= Html::a('Go Back', ['site/faskes'], ['class' => 'btn btn-secondary']) ?>
</p>
