<?php

use backend\models\JatahCutiKaryawan;
use backend\models\MasterKode;
use backend\models\RekapCuti;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCuti $model */

$this->title = 'Pengajuan Cuti ';
$this->params['breadcrumbs'][] = ['label' => 'Pengajuan Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->karyawan->nama, 'url' => ['view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]];
$this->params['breadcrumbs'][] = 'Tanggapan';

$jatahcutitahunini = JatahCutiKaryawan::find()->where(['id_master_cuti' => $model->jenis_cuti, 'id_karyawan' => $model->id_karyawan])->one();
$terpakai = RekapCuti::find()->where(['id_karyawan' => $model->id_karyawan])->one();


?>
<div class="pengajuan-cuti-update">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'id_karyawan')->hiddenInput(['style' => 'display:none'])->label(false) ?>
        <?= $form->field($model, 'alasan_cuti')->hiddenInput(['style' => 'display:none'])->label(false) ?>

        <div class="row ">
            <div class="col-6 w-100">
                <h3> <?= $model->karyawan->nama ?></h3>
                <p>Keterangan : <?= $model->alasan_cuti ?></p>
            </div>
            <div class="col-6 w-100 d-flex flex-column">
                <h6 class="capitalize fw-bold ">JENIS CUTI : <?= $model->jenisCuti->jenis_cuti ?></h6>
                <p>JATAH CUTI TERSISA :
                    <?php
                    if (!$jatahcutitahunini) {
                        echo '<span class="text-warning">Data Jatah Cuti Tahun Ini Tidak Ditemukan, <a target="_blank" href="/panel/jatah-cuti-karyawan/index">Set Disini</a></span>';
                    } else if ($jatahcutitahunini['jatah_hari_cuti'] == 0) {
                        echo '<span>Jatah cuti tahun ini 0 (Telah Habis)</span>';
                    } else {
                        $sisa_cuti = $jatahcutitahunini['jatah_hari_cuti'] - ($terpakai['total_hari_terpakai'] ?? 0);
                        echo '<a target="_blank" href="/panel/rekap-cuti/index"><span>' . $sisa_cuti . ' Hari</span></a>';
                    }
                    ?>
                </p>
            </div>
        </div>

        <hr>

        <div class="col-12">
            <div id="detail-container">
                <?php if (!empty($model->detailCuti)): ?>
                    <?php foreach ($model->detailCuti as $index => $detailModel): ?>
                        <div class="flex-wrap mb-3 detail-item d-flex align-items-center justify-content-between" data-index="<?= $index ?>">
                            <!-- Tanggal -->
                            <div class="mb-2 mb-md-0 col-12 col-md-8">
                                <?= $form->field($detailModel, "[$index]tanggal")->input('date', ['class' => 'form-control form-control-sm'])->label('Tanggal') ?>
                            </div>

                            <!-- Tombol Hapus -->
                            <div class="mt-2 col-12 col-md-auto d-flex align-items-start mt-md-0">
                                <button type="button" class="btn btn-danger btn-sm remove-detail" data-index="<?= $index ?>">Hapus</button>
                            </div>
                        </div>
                        <hr class="my-2" />
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <button type="button" id="add-detail" class="mt-2 btn btn-primary btn-sm">Tambah Detail</button>

        <?php if (!$model->isNewRecord): ?>
            <div class="mt-5 row col-12">
                <div class="col-md-6 col-12">
                    <?php
                    $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');
                    echo $form->field($model, 'status')->radioList($data, [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::radio($name, $checked, [
                                'value' => $value,
                                'label' => $label,
                                'labelOptions' => ['class' => 'radio-label mr-5'],
                            ]);
                        },
                    ])->label('Status Pengajuan');
                    ?>
                </div>

                <div class="col-12 col-md-6">
                    <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 1]) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<<JS
$('#add-detail').click(function() {
    let index = $('.detail-item').length;
    let html = '<div class="flex-wrap mb-3 detail-item d-flex align-items-center justify-content-between" data-index="' + index + '">' +
        // Tanggal (full width di mobile, 8 kolom di md+)
        '<div class="mb-2 mb-md-0 col-12 col-md-8">' +
            '<label for="detailcuti-' + index + '-tanggal" class="form-label">Tanggal</label>' +
            '<input type="date" id="detailcuti-' + index + '-tanggal" name="DetailCuti[' + index + '][tanggal]" class="form-control form-control-sm" />' +
        '</div>' +

        // Tombol hapus (full width di mobile, auto width di md+)
        '<div class="mt-2 col-12 col-md-auto d-flex align-items-start mt-md-0">' +
            '<button type="button" class="btn btn-danger btn-sm remove-detail" data-index="' + index + '">Hapus</button>' +
        '</div>' +
    '</div>' +
    '<hr class="my-2" />';
    
    $('#detail-container').append(html);
});

$(document).on('click', '.remove-detail', function() {
    if(confirm('Yakin ingin menghapus detail ini?')) {
        let index = $(this).data('index');
        $('.detail-item[data-index="' + index + '"]').remove();

        // Reindex items
        $('.detail-item').each(function(i) {
            $(this).attr('data-index', i);
            $(this).find('input, label').each(function() {
                let attrFor = $(this).attr('for');
                let name = $(this).attr('name');
                if(attrFor) {
                    let newFor = attrFor.replace(/\d+/, i);
                    $(this).attr('for', newFor);
                }
                if(name) {
                    let newName = name.replace(/\[\d+\]/, '[' + i + ']');
                    $(this).attr('name', newName);
                }
                if($(this).attr('id')) {
                    let newId = $(this).attr('id').replace(/\d+/, i);
                    $(this).attr('id', newId);
                }
            });
        });
    }
});
JS;
$this->registerJs($js);
?>