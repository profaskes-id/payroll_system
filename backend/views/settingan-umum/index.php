<?php

use backend\models\MasterKode;
use backend\models\SettinganUmum;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmumSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Settingan Lainnya');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingan-umum-index">
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
                    'urlCreator' => function ($action, SettinganUmum $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_settingan_umum' => $model->id_settingan_umum]);
                    }
                ],
                'kode_setting',
                'nama_setting',
                'ket',
                [
                    'attribute' => 'nilai_setting',
                    'value' => function ($model) {
                        return $model->nilai_setting == 1 ? "Aktif" : "Tidak Aktif";
                    },
                ]
            ],
        ]); ?>

    </div>


</div>
<div class="settingan-umum-index">
    <div class="table-container table-responsive" style="margin-top: 30px;">
        <h3>Pengaturan Nilai Lainnya</h3>
        <p class="text-muted">Atur Nilai untuk beberapa pengaturan lain</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">Aksi</th>
                    <th>Nama Group</th>
                    <th>Nilai</th>
                    <th>Deskripsi</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>

                        <?= Html::a(
                            '<i class="fas fa-edit"></i>',
                            ['/your-controller/edit', 'id' => $tanggal_cut_of['kode'] ?? ''], // Update with your actual route
                            [
                                'class' => 'btn btn-sm btn-primary btn-edit', // Added btn-edit class
                                'title' => 'Edit',
                                'data-toggle' => 'tooltip-2'
                            ]
                        ) ?>
                    </td>
                    <td><?= Html::encode($tanggal_cut_of['nama_group']) ?></td>
                    <td><?= Html::encode($tanggal_cut_of['nama_kode']) ?></td>
                    <td class="text-capitalize">Tanggal dimulai perhitungan penggajian dengan menginputkan (tanggal)</td>
                    <td>
                        <span class="badge <?= $tanggal_cut_of['status'] == 1 ? 'badge-success' : 'badge-danger' ?>">
                            <?= $tanggal_cut_of['status'] == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                        </span>
                    </td>

                </tr>

                <tr>
                    <td>

                        <?= Html::a(
                            '<i class="fas fa-edit"></i>',
                            ['/your-controller/edit', 'id' => $potongan_persenan_wfh['kode'] ?? ''], // Update with your actual route
                            [
                                'class' => 'btn btn-sm btn-primary btn-edit b', // Added btn-edit class
                                'title' => 'Edit',
                                'data-toggle' => 'tooltip-wfh'
                            ]
                        ) ?>
                    </td>
                    <td><?= Html::encode($potongan_persenan_wfh['nama_group']) ?></td>
                    <td><?= Html::encode($potongan_persenan_wfh['nama_kode']) ?></td>
                    <td style="text-transform: capitalize;">Potongan Persenan jika karyawan WFH</td>
                    <td>
                        <span class="badge <?= $potongan_persenan_wfh['status'] == 1 ? 'badge-success' : 'badge-danger' ?>">
                            <?= $potongan_persenan_wfh['status'] == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                        </span>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Tanggal Cut Off</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editModalBody">
                <div class="master-kode-form">
                    <?php $form = ActiveForm::begin(['action' => Url::to(['/settingan-umum/edit-cutoff']), 'enableClientScript' => true, 'method' => 'post']); ?>

                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($tanggal_cut_of, 'nama_kode')->textInput(['maxlength' => true, 'placeholder' => 'Masukan Tanggal Cut Off'])->label('Tanggal Dimulai Cut Off') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'add-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?php
// Inisialisasi tooltip
$this->registerJs("$('[data-toggle=\"tooltip\"]').tooltip();");

// JavaScript to handle modal loading
$this->registerJs(
    <<<JS
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        // Load the form via AJAX
        $('#editModalBody').load(url, function() {
            $('#editModal').modal('show');
            
            // Initialize Select2 in the modal if needed
            if ($('#masterkode-nama_group').length) {
                $('#masterkode-nama_group').select2({
                    language: 'id',
                    placeholder: 'Masukan Nama Group ...',
                    allowClear: true
                });
            }
        });
    });
    
    // Handle modal form submission
    $(document).on('beforeSubmit', '#editModalBody form', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    // Reload the page or update specific elements
                    location.reload();
                } else {
                    // Update the form with validation errors
                    $('#editModalBody').html(response.form);
                }
            }
        });
        return false;
    });
JS
);
$this->registerJs("$('[data-toggle=\"tooltip-2\"]').tooltip();");

// JavaScript to handle modal loading
$this->registerJs(
    <<<JS
    $(document).on('click', '.btn-wfh', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        // Load the form via AJAX
        $('#editModalBody').load(url, function() {
            $('#editModal').modal('show');
            
            // Initialize Select2 in the modal if needed
            if ($('#masterkode-nama_group').length) {
                $('#masterkode-nama_group').select2({
                    language: 'id',
                    placeholder: 'Masukan Nama Group ...',
                    allowClear: true
                });
            }
        });
    });
    
    // Handle modal form submission
    $(document).on('beforeSubmit', '#editModalBody form', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editModal').modal('hide');
                    // Reload the page or update specific elements
                    location.reload();
                } else {
                    // Update the form with validation errors
                    $('#editModalBody').html(response.form);
                }
            }
        });
        return false;
    });
JS
);
