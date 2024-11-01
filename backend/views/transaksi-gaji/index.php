<?php

use backend\models\Karyawan;
use backend\models\TransaksiGaji;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGajiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Transaksi Gaji');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-gaji-index">



    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php // $this->render('_search', ['model' => $searchModel]); 
                ?>
            </div>
        </div>
    </div>
    <div class="table-container table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],

                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Karyawan $model, $key, $index, $column) {

                        return Url::toRoute(['/transaksi-gaji/create', 'id_karyawan' => $model->id_karyawan]);
                    }
                ],


                [
                    'label' => "karyawan",
                    'value' => function ($model) {
                        return $model->nama;
                    }
                ]

                // 'id_transaksi_gaji',
                // 'nomer_identitas',
                // 'nama',
                // 'bagian',
                // 'jabatan',
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
            ],
        ]); ?>


    </div>
</div>