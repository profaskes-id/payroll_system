<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\Karyawan;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinas $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pengajuan-dinas-form table-container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-12 ">
            <?php
            $data = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $data,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih Karyawan ...',],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Nama');
            ?>
        </div>

        <!-- Container untuk detail dinas -->
        <div class="col-12">
            <h5>Detail Dinas</h5>
            <div id="detail-container">



                <?php if (!empty($detailModels)): ?>
                    <?php foreach ($detailModels as $index => $detailModel): ?>
                        <div class="mb-3 row detail-item" data-index="<?= $index ?>">
                            <div class="col-md-4">
                                <?= $form->field($detailModel, "[$index]tanggal")->input('date', ['class' => 'form-control form-control-sm'])->label('Tanggal') ?>
                            </div>


                            <div class="col-md-5">

                                <label class="d-block ">Status</label>
                                <div class="justify-content-around d-flex ">

                                    <div class="form-check">
                                        <?= Html::radio("DetailDinas[$index][status]", $detailModel->status == 0, [
                                            'value' => 1,
                                            'class' => 'form-check-input',
                                            'id' => "status_pending_$index",
                                            'label' => 'Pending'
                                        ]) ?>
                                    </div>
                                    <div class="form-check">
                                        <?= Html::radio("DetailDinas[$index][status]", $detailModel->status == 1, [
                                            'value' => 1,
                                            'class' => 'form-check-input',
                                            'id' => "status_approved_$index",
                                            'label' => 'Disetujui'
                                        ]) ?>
                                    </div>
                                    <div class="form-check">
                                        <?= Html::radio("DetailDinas[$index][status]", $detailModel->status == 2, [
                                            'value' => 2,
                                            'class' => 'form-check-input',
                                            'id' => "status_rejected_$index",
                                            'label' => 'Ditolak'
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-detail" data-index="<?= $index ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Detail pertama yang sudah ada (kosong) -->
                    <div class="mb-3 row detail-item" data-index="0">
                        <div class="col-md-4">
                            <label for="detaildinas-0-tanggal">Tanggal</label>
                            <input type="date" id="detaildinas-0-tanggal" name="DetailDinas[0][tanggal]" class="form-control form-control-sm" />
                        </div>

                        <div class="col-md-3">
                            <label class="d-block">Status</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="DetailDinas[0][status]" id="status_pending_0" value="0" checked>
                                <label class="form-check-label" for="status_pending_0">Pending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="DetailDinas[0][status]" id="status_approved_0" value="1">
                                <label class="form-check-label" for="status_approved_0">Disetujui</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="DetailDinas[0][status]" id="status_rejected_0" value="2">
                                <label class="form-check-label" for="status_rejected_0">Ditolak</label>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-detail" data-index="0">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12">
            <button type="button" id="add-detail" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Detail Dinas
            </button>
        </div>

        <div class="mt-4 col-12">
            <?= $form->field($model, 'keterangan_perjalanan')->textarea(['rows' => 1]) ?>
        </div>
        <div class=" col-12">
            <?= $form->field($model, 'estimasi_biaya')->textInput(['maxlength' => true, "type" => "number", "step" => "0.01", "min" => "0", "placeholder" => "0.00"]) ?>
        </div>


        <?php if (!$model->isNewRecord) : ?>
            <div class="col-12 ">
                <?= $form->field($model, 'biaya_yang_disetujui')->textInput(['maxlength' => true, "type" => "number", "step" => "0.01", "min" => "0", "placeholder" => "0.00", 'value' => $model->estimasi_biaya]) ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'catatan_admin')->textarea(['rows' => 1]) ?>
            </div>

            <div class="col-12">
                <?php
                $data = \yii\helpers\ArrayHelper::map(MasterKode::find()->where(['nama_group' => Yii::$app->params['status-pengajuan']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all(), 'kode', 'nama_kode');

                echo $form->field($model, 'status')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                        if ($model->isNewRecord) {
                            $isChecked = $value == 1 ? true : $checked;
                        } else {
                            $isChecked = $checked;
                        }

                        return Html::radio($name, $isChecked, [
                            'value' => $value,
                            'label' => $label,
                            'labelOptions' => ['class' => 'radio-label mr-4'],
                        ]);
                    },
                ])->label('Status Pengajuan');
                ?>
            </div>

            <div class="col-12">

                <?php
                // Ubah data radio list
                $data = [
                    0 => 'Tidak',
                    1 => 'Iya'
                ];

                echo $form->field($model, 'isNewAbsen')->radioList($data, [
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {

                        // Jika form baru, set default value = 1
                        if ($model->isNewRecord) {
                            $isChecked = $value == 1 ? true : false;
                        } else {
                            $isChecked = $checked;
                        }

                        return Html::radio($name, $isChecked, [
                            'value' => $value,
                            'label' => $label,
                            'labelOptions' => ['class' => 'radio-label mr-4'],
                        ]);
                    },
                ])->label('Status Pengajuan (<small>Apakah Perlu Dibuatkan Absen Untuk tanggal yang di ajukan dengan keterangan Dinas Luar</small>)');
                ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Submit
                </span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
$('#add-detail').click(function() {
    let index = $('.detail-item').length;
    let html = `
    <div class="mb-3 row detail-item" data-index="\${index}">
        <div class="col-md-4">
            <label for="detaildinas-\${index}-tanggal">Tanggal</label>
            <input type="date" id="detaildinas-\${index}-tanggal" name="DetailDinas[\${index}][tanggal]" class="form-control form-control-sm" />
        </div>
      
        <div class="col-md-3">
            <label class="d-block">Status</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="DetailDinas[\${index}][status]" id="status_pending_\${index}" value="0" checked>
                <label class="form-check-label" for="status_pending_\${index}">Pending</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="DetailDinas[\${index}][status]" id="status_approved_\${index}" value="1">
                <label class="form-check-label" for="status_approved_\${index}">Disetujui</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="DetailDinas[\${index}][status]" id="status_rejected_\${index}" value="2">
                <label class="form-check-label" for="status_rejected_\${index}">Ditolak</label>
            </div>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-detail" data-index="\${index}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    `;
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