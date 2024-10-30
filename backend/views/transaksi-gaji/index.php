<?php

use backend\models\TransaksiGaji;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGajiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Transaksi Gajis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-gaji-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transaksi Gaji'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_transaksi_gaji',
            'nomer_identitas',
            'nama',
            'bagian',
            'jabatan',
            //'jam_kerja',
            //'status_karyawan',
            //'periode_gaji_bulan',
            //'periode_gaji_tahun',
            //'jumlah_hari_kerja',
            //'jumlah_hadir',
            //'jumlah_sakit',
            //'jumlah_wfh',
            //'jumlah_cuti',
            //'gaji_pokok',
            //'jumlah_jam_lembur',
            //'lembur_perjam',
            //'total_lembur',
            //'jumlah_tunjangan',
            //'jumlah_potongan',
            //'potongan_wfh_hari',
            //'jumlah_potongan_wfh',
            //'gaji_diterima',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TransaksiGaji $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_transaksi_gaji' => $model->id_transaksi_gaji]);
                 }
            ],
        ],
    ]); ?>


</div>
