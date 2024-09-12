<?php

use backend\models\RiwayatKesehatan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatKesehatanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Riwayat Kesehatans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-kesehatan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Riwayat Kesehatan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_riwayat_kesehatan',
            'id_karyawan',
            'nama_pengecekan',
            'keterangan:ntext',
            'surat_dokter',
        ],
    ]); ?>


</div>