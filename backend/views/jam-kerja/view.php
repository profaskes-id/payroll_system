<?php

// use backend\models\HariLibur;
use backend\models\JadwalKerja;
use backend\models\ShiftKerja;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = $model->nama_jam_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jam-kerja-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">


        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

            <p class="d-flex justify-content-end " style="gap: 10px;">
                <?= Html::a('tambah total hari kerja', ['total-hari-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja, 'jenis_shift' => $model->jenis_shift], ['class' => 'tambah-button']) ?>

                <?= Html::a('Update', ['update', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'id_jam_kerja' => $model->id_jam_kerja], [
                    'class' => 'reset-button',
                    'data' => [
                        'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'nama_jam_kerja',
                    [
                        'label' => 'Jenis Shift',
                        'value' => function ($model) {
                            return $model->jenisShift->nama_kode;
                        }
                    ],
                ],
            ]) ?>


            <br>
            <div class="d-flex align-items-center justify-content-between">
                <h4>Jadwal Kerja</h4>
                <?php if (strtolower($model->jenisShift->nama_kode) == "shift"): ?>

                    <button data-toggle="modal" data-target="#exampleModal" class="tambah-button mb-3">
                        Tambah Data Hari dan Shift
                    </button>




                <?php else: ?>
                    <p class="d-flex justify-content-end " style="gap: 10px;">
                        <?= Html::a('Add new', ['/jadwal-kerja/create', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'tambah-button']) ?>
                    </p>
                <?php endif; ?>
            </div>


            <?php
            $jenisShift = new ShiftKerja();
            if (strtolower($model->jenisShift->nama_kode) == "shift"): ?>

                <?= GridView::widget([
                    'dataProvider' => $jadwalKerjaProvider,
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
                            'urlCreator' => function ($action, JadwalKerja $model, $key, $index, $column) {
                                return Url::toRoute(['jadwal-kerja/view', 'id_jadwal_kerja' => $model->id_jadwal_kerja]);
                            }
                        ],
                        [
                            'label' => 'Nama Hari',
                            'value' => function ($model) {
                                return $model->getNamaHari($model->nama_hari);
                            }
                        ],
                        [
                            'label' => 'SHIFT',
                            'value' => function ($model)  use ($jenisShift) {
                                $dataShift = $jenisShift->getShiftKerjaById($model['id_shift_kerja']);
                                return $dataShift['nama_shift'];
                            }
                        ]

                    ],
                ]); ?>
            <?php else: ?>

                <?= GridView::widget([
                    'dataProvider' => $jadwalKerjaProvider,
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
                            'urlCreator' => function ($action, JadwalKerja $model, $key, $index, $column) {
                                return Url::toRoute(['jadwal-kerja/view', 'id_jadwal_kerja' => $model->id_jadwal_kerja]);
                            }
                        ],
                        [
                            'label' => 'Nama Hari',
                            'value' => function ($model) {
                                return $model->getNamaHari($model->nama_hari);
                            },
                        ],

                        [
                            'label' => 'Jam Masuk',
                            'value' => function ($model) {
                                if ($model['is_24jam'] == 1) {
                                    return "Tidak Ada Batasan Jam";
                                } else {
                                    return $model['jam_masuk'];
                                }
                            },
                        ],
                        [
                            'label' => 'Jam Pulang',
                            'value' => function ($model) {
                                if ($model['is_24jam'] == 1) {
                                    return "Tidak Ada Batasan Jam";
                                } else {
                                    return $model['jam_keluar'];
                                }
                            },
                        ],

                    ],
                ]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Shift Dalam Hari</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- form untuk jadwal kerja -->
                <?php
                $hari = [];

                for ($i = 0; $i < 7; $i++) {
                    $hari[$i] = $jadwalKerja->getNamaHari($i);
                }
                ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['/jadwal-kerja/shift', 'id_jam_kerja' => $model->id_jam_kerja],
                    'method' => 'post',
                ]); ?>
                <div class="col-12">
                    <?= $form->field($jadwalKerja, 'nama_hari')->dropDownList($hari, ["required" => true, 'prompt' => 'Pilih Hari '])->label('Pilih Hari <span class="text-danger">*</span>') ?>
                </div>

                <div class="col-12">

                    <?php
                    $shiftKerja = new ShiftKerja();
                    $data = $shiftKerja->getShiftKerjaAll();
                    $nama_kode = \yii\helpers\ArrayHelper::map($data, 'id_shift_kerja', 'tampilan');
                    echo $form->field($jadwalKerja, 'shift_sehari')->checkboxList($nama_kode, [
                        'multiple' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return Html::checkbox($name, $checked, [
                                'class' => 'checkbox',
                                'value' => $value,
                                'label' => $label,
                                'labelOptions' => [
                                    'class' => 'checkbox-inline',
                                    'style' => 'display: block; margin-bottom: 10px;, cursor: pointer' // Tambahkan margin bawah
                                ],
                            ]);
                        }
                    ])->label('Pilih Shift <span class="text-danger">*</span>');
                    ?>
                </div>

                <div class="d-flex justify-content-end" style="gap:10px;">
                    <button type="button" class="reset-button" data-dismiss="modal">cancel</button>
                    <button type="submit" class="add-button">save</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>


        </div>

    </div>
</div>
</div>