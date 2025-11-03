<?php

use backend\models\helpers\KaryawanHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbon $model */

$nama = KaryawanHelper::getKaryawanById($model->id_karyawan)[0]['nama'];


$this->title = 'Pembayaran Kasbon  ';
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Kasbon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $nama;
\yii\web\YiiAsset::register($this);
?>
<div class="pembayaran-kasbon-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Bayar', ['create-new-payment', 'id_pembayaran_kasbon' => $model->id_pembayaran_kasbon], ['class' => 'add-button']) ?>
            <?= Html::a('Delete data Terbaru', ['delete-latest', 'id_karyawan' => $model->id_karyawan], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>


        <?php
        // Contoh data (misal $model atau array)


        // Format Rupiah helper
        function rupiah($angka)
        {
            return 'Rp ' . number_format((float)$angka, 0, ',', '.');
        }

        // Tentukan status
        $statusText = $model['status_potongan'] == 1
            ? '<span style="color:green;font-weight:bold;">LUNAS</span>'
            : '<span style="color:red;font-weight:bold;">BELUM LUNAS</span>';
        ?>

        <!-- Tampilkan di tag <p> -->

        <div class="mt-5 row">

            <p class="col-12 col-md-4"><strong>Jumlah Kasbon:</strong> <?= rupiah($model['jumlah_kasbon']) ?></p>
            <p class="col-12 col-md-4"><strong>Status Potongan:</strong> <?= $statusText ?></p>
            <p class="col-12 col-md-4"><strong>Sisa Kasbon:</strong> <?= rupiah($model['sisa_kasbon']) ?></p>
        </div>

        <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'Waktu',
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($model) {
                        // Cek tipe
                        if (is_object($model) && isset($model->created_at)) {
                            return Yii::$app->formatter->asDatetime($model->created_at);
                        } elseif (is_array($model) && isset($model['created_at'])) {
                            return Yii::$app->formatter->asDatetime($model['created_at']);
                        } else {
                            // fallback aman
                            return '-';
                        }
                    }
                ],


                [
                    'attribute' => 'deskripsi',
                    'label' => 'Deskripsi',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $text = strtolower($model->deskripsi ?? ''); // ubah ke huruf kecil
                        $color = 'black'; // default

                        if (strpos($text, 'top-up') !== false) {
                            // Ngutang lagi
                            $color = 'green';
                        } elseif (strpos($text, 'pembayaran') !== false) {
                            // Bayar
                            $color = 'red';
                        }

                        return "<span style='color: {$color}; text-transform: capitalize;'>{$text}</span>";
                    },
                ],

                [
                    'attribute' => 'jumlah_potong',
                    'label' => 'Jumlah Potong / angsuran',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return 'Rp ' . number_format($model->jumlah_potong, 0, ',', '.');
                    }
                ],




            ],
            'tableOptions' => ['class' => 'table table-striped table-bordered'],
        ]); ?>

    </div>