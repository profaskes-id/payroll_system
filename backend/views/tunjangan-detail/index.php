<?php

use backend\models\GajiPotongan;
use backend\models\GajiTunjangan;
use backend\models\helpers\KaryawanHelper;
use backend\models\Terbilang;
use backend\models\TunjanganDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tunjangan Karyawan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-detail-index">

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
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>

    <br>

    <div class="table-container table-responsive">
        <!-- // ['label' => 'Tunjangan', 'icon' => 'fa fa-hand-holding-usd', 'url' => ['/tunjangan/index'],], -->
        <div class="d-flex justify-content-start">
            <a href="/panel/tunjangan/create" target="_blank" class="tambah-button"><i class="fa fa-plus"> Tunjangan Baru</i></a>
            <a href="/panel/tunjangan/index" target="_blank" class="reset-button"><i class="fa fa-list"> list Tunjangan </i></a>
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
                    'class' => 'yii\grid\Column',
                    'header' => 'Aksi',
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'content' => function ($model, $key, $index, $column) {
                        return "<div class='d-flex '>" .
                            Html::a(
                                '<i class="fas fa-edit"></i>', // Icon tong sampah (menggunakan Font Awesome)
                                ['update', 'id_tunjangan_detail' => $model->id_tunjangan_detail,],
                                [
                                    'class' => 'edit-button mr-2',
                                ]
                            ) .
                            Html::a(
                                '<i class="fas fa-trash"></i>', // Icon tong sampah (menggunakan Font Awesome)
                                ['delete', 'id_tunjangan_detail' => $model->id_tunjangan_detail,],
                                [
                                    'class' => 'hapus-button',
                                    'data' => [
                                        'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) .
                            "</div>";
                    },
                ],
                [
                    'label' => "Tunjangan",
                    'value' => function ($model) {
                        return $model->tunjangan->nama_tunjangan;
                    },
                ],
                [
                    'label' => "Karyawan",
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    },
                ],
                [
                    'attribute' => 'jumlah',
                    'value' => function ($model) {
                        return 'Rp. ' . number_format($model->jumlah, 2, ',', '.');
                    },
                ],
            ],
        ]); ?>


        <?php if ($id_karyawan): ?>
            <?php
            $karyawan = KaryawanHelper::getKaryawanById($id_karyawan)[0];
            $gajiTunjangan = new GajiTunjangan();
            $sumall = $gajiTunjangan->getSumTunjangan($karyawan['id_karyawan']);
            $terbilang = Terbilang::toTerbilang($sumall) . ' Rupiah';

            ?>
            <table class="table">
                <tr>
                    <th> Total Tunjangan Yang Didapatkan : <?= $karyawan['nama'] ?> sebesar <span class="text-danger">Rp. <?= number_format($sumall, 2, ',', '.') ?> ( <?= $terbilang ?> )</span></th>
                </tr>
            </table>
        <?php endif; ?>