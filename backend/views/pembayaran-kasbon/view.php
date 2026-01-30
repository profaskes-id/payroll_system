<?php

use backend\models\helpers\KaryawanHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbon $model */

$nama = KaryawanHelper::getKaryawanById($model->id_karyawan)[0]['nama'];


$this->title = 'Pembayaran Kasbon Manual  ';
$this->params['breadcrumbs'][] = ['label' => 'Pembayaran Kasbon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $nama;
\yii\web\YiiAsset::register($this);
?>
<script src="https://cdn.jsdelivr.net/npm/terbilang-js@1.0.1/terbilang.min.js"></script>

<div class="pembayaran-kasbon-view">




    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">


        <p style="
    background-color: #fff3cd;
    color: #856404;
    padding: 12px 16px;
    border: 1px solid #ffeeba;
    border-radius: 6px;
    font-size: 14px;
    line-height: 1.5;
">
            <strong>Perhatian:</strong> Pembayaran kasbon manual <strong>tidak memotong gaji karyawan</strong> dan
            <strong>tidak tercantum dalam slip gaji</strong>. Pembayaran dilakukan
            <strong>langsung oleh karyawan</strong> dan <strong>akan mengurangi sisa kasbon</strong>.
        </p>

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
            <div class="col-12 col-md-6">

                <p><strong>Jumlah Kasbon:</strong> <?= rupiah($model['jumlah_kasbon']) ?></p>
                <p class="gap-2 d-flex align-items-center">
                    <strong class="me-2">Angsuran Kasbon Setiap Bulan:</strong>
                    <?= rupiah($model->kasbon->angsuran_perbulan ?? 0) ?>

                    <button
                        type="button"
                        class="p-0 btn btn-sm btn-link ms-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditAngsuran"
                        title="Edit Angsuran">
                        <!-- SVG pencil icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0d6efd" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706l-1.439 1.439-2.122-2.122 1.439-1.439a.5.5 0 0 1 .707 0l1.415 1.415z" />
                            <path d="M14.061 4.085 11.94 1.964 4.5 9.404V11.5h2.096l7.465-7.415z" />
                            <path fill-rule="evenodd" d="M1 13.5A.5.5 0 0 1 1.5 13H11a.5.5 0 0 1 0 1H1.5a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </button>
                </p>
            </div>
            <p class="col-12 col-md-3"><strong>Sisa Kasbon:</strong> <?= rupiah($model['sisa_kasbon']) ?> </p>
            <p class="col-12 col-md-3"><strong>Status Potongan:</strong> <?= $statusText ?></p>
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




    <?php
    Modal::begin([
        'id' => 'modalEditAngsuran',
        'title' => 'Edit Angsuran Kasbon',
        'size' => Modal::SIZE_DEFAULT,
    ]);
    ?>

    <?php $form = ActiveForm::begin([
        'action' => ['pembayaran-kasbon/update-angsuran', 'id_pengajuan_kasbon' => $model->kasbon->id_pengajuan_kasbon],
        'method' => 'post',
    ]); ?>

    <?= $form->field($model->kasbon, 'angsuran_perbulan')->textInput([
        'type' => 'number',
        'min' => 0,
        'placeholder' => 'Masukkan angsuran per bulan',
        'class' => 'form-control number-input',
    ]) ?>
    <p id="terbilang-angsuran_perbulan" class="mt-1 text-muted"></p>
    <div class="gap-2 d-flex justify-content-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Batal
        </button>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Modal::end(); ?>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.number-input').forEach(function(input) {
                const fieldKey = input.id.split('-').pop();
                const output = document.getElementById('terbilang-' + fieldKey);
                if (!output) return;

                const updateText = function() {
                    const val = input.value;
                    output.textContent = (val && val > 0) ?
                        terbilang(Math.floor(val)) + ' rupiah' :
                        '';
                };

                updateText();
                input.addEventListener('input', updateText);
            });
        });
    </script>