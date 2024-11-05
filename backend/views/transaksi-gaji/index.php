<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\helpers\PeriodeGajiHelper;
use backend\models\Karyawan;
use backend\models\PeriodeGaji;
use backend\models\Tanggal;
use backend\models\TransaksiGaji;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\TransaksiGajiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Transaksi Gaji');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-gaji-index">


    <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',   'action' => ['transaksi-gaji/index']]); ?>
    <div class="table-container table-responsive">
        <div class="row mb-2">

            <div class="col-md-5 col-12">
                <?php
                // Memastikan data yang digunakan untuk Select2 sesuai dengan kondisi $karyawanID
                if ($karyawanID != null) {
                    $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanById($karyawanID), 'id_karyawan', 'nama');
                    $selectedValue = $karyawanID; // Jika $karyawanID ada, set nilai yang dipilih ke karyawanID
                } else {
                    $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
                    $selectedValue = null; // Jika $karyawanID tidak ada, tidak ada nilai yang dipilih
                }

                echo $form->field($karyawan, 'id_karyawan')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => [
                        'placeholder' => 'Cari karyawan ...',
                        'value' => $selectedValue, // Menetapkan nilai yang dipilih sesuai dengan kondisi $karyawanID
                    ],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>

            </div>
            <div class="col-md-5 col-12">
                <?php

                $nama_group = \yii\helpers\ArrayHelper::map($model->getPeriodeGajidpw(), 'id_periode_gaji', 'tampilan');
                if ($periode_gajiID != null) {
                    $selectedValuePeriode = $periode_gajiID; // Jika $karyawanID ada, set nilai yang dipilih ke karyawanID
                } else {
                    $selectedValuePeriode = null; // Jika $karyawanID tidak ada, tidak ada nilai yang dipilih
                }
                echo $form->field($periode_gaji, 'id_periode_gaji')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => [
                        'placeholder' => 'Cari Periode Gaji ...',
                        'value' => $selectedValuePeriode, // Menetapkan nilai yang dipilih sesuai dengan kondisi $karyawanID
                    ],
                    'pluginOptions' => [
                        // 'tags' => true,
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>


            <div class="col-2 col-md-1">
                <button class="add-button" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="col-2 col-md-1">
                <a href="/panel/transaksi-gaji">
                    <button class="reset-button" type="button">
                        <i class="fas fa-undo"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>




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
                    // 'class' => ActionColumn::className(),
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model['id_transaksi_gaji'] != null) {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #ff0000 !important; color:#252525; all:unset;  display: block;">
                            <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
                            </span>
                            </button>', ['detail', 'id_transaksi_gaji' => $model['id_transaksi_gaji'],]);
                        } else {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset;  display: block;">
                            <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
                            </span>
                            </button>', ['create', 'periode_gaji' => $model['id_periode_gaji'], 'id_karyawan' => $model['id_karyawan']],);
                        }
                    }
                ],


                [
                    'label' => "karyawan",
                    'value' => function ($model) {
                        return $model['nama'];
                    }
                ],


                [
                    'label' => "Periode Gaji",
                    'value' => function ($model) {
                        if ($model['id_periode_gaji']) {
                            $tanggal = new Tanggal();
                            $bulan = $tanggal->getBulan($model['bulan']);
                            return $bulan . " " . $model['tahun'];
                        } else {
                            return "-";
                        }
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' =>  'jumlah_tunjangan',
                    'value' => function ($model) {
                        if ($model['id_transaksi_gaji']) {
                            return number_format($model['jumlah_tunjangan'], 0, ',', '.');
                        }
                        return "<span class='text-danger'>Belum Di Set</span>";
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' =>  'jumlah_potongan',
                    'value' => function ($model) {
                        if ($model['id_transaksi_gaji']) {
                            return number_format($model['jumlah_potongan'], 0, ',', '.');
                        }
                        return "<span class='text-danger'>Belum Di Set</span>";
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'gaji_diterima',
                    'value' => function ($model) {
                        if ($model['id_transaksi_gaji']) {
                            return "Rp " . number_format($model['gaji_diterima'], 0, ',', '.');
                        }
                        return "<span class='text-danger'>Belum Di Set</span>";
                    }
                ],

            ],
        ]); ?>


    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen input dengan ID tanggal-input
        var tanggalInput = document.getElementById('tanggal-input');
        var bagian = document.getElementById('bagian-input');

        // Tambahkan event listener untuk perubahan

        function autosubmit() {
            var form = tanggalInput.closest('form');

            // Kirim form menggunakan metode GET
            if (form) {
                setTimeout(function() {
                    form.submit();
                }, 1500);
            }
        }

    });
</script>