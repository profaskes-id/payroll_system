<?php

use backend\models\GajiTunjangan;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Tanggal;
use backend\models\TransaksiGaji;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjanganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tunjangan Gaji');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-tunjangan-index">
    <!-- <div class="costume-container">
        <p class="">
            <?php // Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) 
            ?>
        </p>
    </div> -->

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

    <div class='table-container'>

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
                                ['update', 'id_gaji_tunjangan' => $model->id_gaji_tunjangan,],
                                [
                                    'class' => 'edit-button mr-2',
                                ]
                            ) .
                            Html::a(
                                '<i class="fas fa-trash"></i>', // Icon tong sampah (menggunakan Font Awesome)
                                ['delete', 'id_gaji_tunjangan' => $model->id_gaji_tunjangan,],
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
                    'label' => 'Transaksi Gaji',
                    'value' => function ($model) {
                        return $model->transaksiGaji->nama ?? 'Not Found';
                    }
                ],
                [
                    'label' => 'Tunjangan',
                    'value' => function ($model) {
                        return $model->tunjanganDetail->tunjangan->nama_tunjangan ?? 'Not Found';
                    }
                ],
                [
                    'label' => "Periode Gaji",
                    'format' => 'raw',
                    'value' => function ($model) {
                        $id_periode_gaji = $model->transaksiGaji['periode_gaji'] ?? 0;

                        if ($id_periode_gaji == 0) {
                            return "tidak Ditemukan";
                        }

                        $data =  PeriodeGajiHelper::getPeriodeGaji($id_periode_gaji);
                        $tanggal = new Tanggal();

                        $result = "<div class> 
                        <p class='m-0'>{$tanggal->getBulan($data['bulan'])} {$data['tahun']} </p>
                        <span class='text-xs'>({$tanggal->getIndonesiaFormatTanggal($data['tanggal_awal'])} - {$tanggal->getIndonesiaFormatTanggal($data['tanggal_akhir'])})    
                        </div>";

                        return $result;
                    }
                ],

                // // 'id_transaksi_gaji',
                // 'id_tunjangan_detail',
                // 'nama_tunjangan',
                [
                    'attribute' => 'jumlah',
                    'value' => function ($model) {
                        return 'Rp. ' . number_format($model->jumlah, 2, ',', '.');
                    },
                ],
            ],
        ]); ?>

    </div>

</div>