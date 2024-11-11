<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Cetak $model */

$this->title = $model->karyawan->nama . ' (' . $model->dataPekerjaan->bagian->nama_bagian . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cetak'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cetak-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/karyawan/view', 'id_karyawan' => $model->id_karyawan], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('cetak', ['kontrak-download', 'id_cetak' => $model->id_cetak,], ['class' => 'cetak-button', 'target' => '_blank']) ?>
            <?= Html::a('Update', ['update', 'id_cetak' => $model->id_cetak,], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_cetak' => $model->id_cetak,], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <div class="row">
            <div class="col-lg-6">

                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th>{label}</th><td>{value}</td></tr>',

                    'attributes' => [
                        [
                            'label' => 'Nama',
                            'value' => function ($model) {
                                return "<p class='m-0 p-0 text-capitalize'>{$model->karyawan->nama}</p>";
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'nomor identitas',
                            'value' => function ($model) {
                                return "<p class='m-0 p-0 text-capitalize'>{$model->karyawan->jenisidentitas->nama_kode}, {$model->karyawan->nomer_identitas}</p>";
                            },
                            'format' => 'raw',
                        ],

                        [
                            'label' => 'Jenis Kelamin',
                            'value' => function ($model) {
                                return $model->karyawan->kode_jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                            },
                        ],


                        [
                            'label' => 'Tempat dan Tanggal Lahir',
                            'value' => function ($model) {
                                $tanggal = new Tanggal();
                                return  $model->karyawan->tempat_lahir . ', ' . $tanggal->getIndonesiaFormatLong($model->karyawan->tanggal_lahir);
                            }
                        ],

                        [
                            'label' => 'Alamat',
                            'value' => function ($model) {
                                if ($model->karyawan->is_current_domisili == 1) {
                                    return $model->karyawan['alamat_identitas'] . ', ' . $model->karyawan['desa_lurah_identitas'] . ', ' . $model->karyawan->kecamatanidentitas->nama_kec . ', ' . $model->karyawan->kabupatenidentitas->nama_kab . ', ' . $model->karyawan->provinsiidentitas->nama_prop;
                                } else {
                                    return $model->karyawan['alamat_domisili'] . ' ' . $model->karyawan['desa_lurah_domisili'] . ', ' . $model->karyawan->kecamatandomisili->nama_kec . ', ' . $model->karyawan->kabupatendomisili->nama_kab . ', ' . $model->karyawan->provinsidomisili->nama_prop;
                                }
                            }
                        ]


                    ],
                ]) ?>
            </div>
            <div class="col-lg-6">

                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th>{label}</th><td>{value}</td></tr>',

                    'attributes' => [
                        [
                            'label' => 'Bagian',
                            'value' => $model->dataPekerjaan->bagian->nama_bagian
                        ],

                        [
                            'attribute' => 'status',
                            'value' => function ($model) {
                                return $model->dataPekerjaan->statusPekerjaan->nama_kode;
                            }
                        ],
                        [
                            'label' => 'Jabatan',
                            'value' => $model->dataPekerjaan->jabatanPekerja->nama_kode
                        ],




                    ],
                ]) ?>
            </div>
            <div class="col-12">

                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th>{label}</th><td>{value}</td></tr>',

                    'attributes' => [
                        'nomor_surat',
                        'nama_penanda_tangan',
                        'jabatan_penanda_tangan',
                        'tempat_dan_tanggal_surat',
                    ],
                ]) ?>
            </div>
        </div>

    </div>
</div>