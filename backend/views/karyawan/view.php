<?php

use backend\models\DataKeluarga;
use backend\models\DataPekerjaan;
use backend\models\PengalamanKerja;
use backend\models\RiwayatKesehatan;
use backend\models\RiwayatPelatihan;
use backend\models\RiwayatPendidikan;
use backend\models\Tanggal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */

$this->title = $model->nama . ' (' . $model->kode_karyawan . ')';
$this->params['breadcrumbs'][] = ['label' => 'karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="karyawan-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-inline-block">
            <?= Html::a('Print PDF <i class="fa fa-print"></i>', ['report', 'id_karyawan' => $model->id_karyawan], ['target' => '_blank', 'class' => 'cetak-button']) ?>
        </p>
        <div class="w-100">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Data Pribadi</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Pengalaman Kerja</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Riwayat Pendidikan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Data Keluarga</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-pelatihan-tab" data-toggle="pill" href="#custom-tabs-one-pelatihan" role="tab" aria-controls="custom-tabs-one-pelatihan" aria-selected="false">Riwayat Pelatihan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-kesehatan-tab" data-toggle="pill" href="#custom-tabs-one-kesehatan" role="tab" aria-controls="custom-tabs-one-kesehatan" aria-selected="false">Riwayat Kesehatan</a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Update', ['update', 'id_karyawan' => $model->id_karyawan], ['class' => 'add-button']) ?>
                                <?= Html::a('Delete', ['delete', 'id_karyawan' => $model->id_karyawan], [
                                    'class' => 'reset-button',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                                        'attributes' => [
                                            'kode_karyawan',
                                            [
                                                'label' => 'Nama',
                                                'value' => function ($model) {
                                                    return "<p class='m-0 p-0 text-capitalize'>{$model->nama}</p>";
                                                },
                                                'format' => 'raw',
                                            ],
                                            [
                                                'label' => 'Nomor Identitas',
                                                'value' => function ($model) {
                                                    return $model->nomer_identitas;
                                                }
                                            ],
                                            [

                                                'label' => 'Jenis Identitas',
                                                'value' => function ($model) {
                                                    return $model->jenisidentitas->nama_kode ?? '';
                                                }
                                            ],
                                            [
                                                'label' => 'Jenis Kelamin',
                                                'value' => function ($model) {
                                                    return $model->kode_jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                                                },
                                            ],


                                            [
                                                'label' => 'Tanggal Lahir',
                                                'value' => function ($model) {
                                                    $tanggalFormat = new Tanggal();
                                                    return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_lahir);
                                                }
                                            ],
                                            [
                                                'attribute' => 'nomer_telepon',
                                                'label' => 'Nomor Telepon',
                                            ],
                                            [
                                                'attribute' => 'email',
                                                'label' => 'Email',
                                                'format' => 'email',
                                            ],
                                            [
                                                'label' => 'Agama',
                                                'value' => function ($model) {
                                                    return $model->masterAgama->nama_kode;
                                                }
                                            ],
                                            [
                                                'attribute' => 'suku',
                                                'label' => 'Suku',
                                            ],
                                            [
                                                'label' => 'Status Nikah',
                                                'value' => function ($model) {
                                                    return $model->statusNikah->nama_kode;
                                                }
                                            ],
                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                                        'attributes' => [

                                            [
                                                'label' => 'Negara',
                                                'value' => function ($model) {
                                                    return "<p class=' py-0 my-0 text-uppercase'>{$model->kode_negara}</p>";
                                                },
                                                'format' => 'raw',
                                            ],
                                            [
                                                'label' => 'Provinsi',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->provinsidomisili->nama_prop ?? '-';
                                                    }
                                                    return $model->provinsiidentitas->nama_prop;
                                                }
                                            ],
                                            [
                                                'label' => 'Kabupaten / Kota',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->kabupatendomisili->nama_kab ?? '-';
                                                    }
                                                    return $model->kabupatenidentitas->nama_kab;
                                                }
                                            ],
                                            [
                                                'label' => 'Kecamatam',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->kecamatandomisili->nama_kec ?? '-';
                                                    }
                                                    return $model->kecamatanidentitas->nama_kec ?? '-';
                                                }
                                            ],
                                            [
                                                'label' => 'Alamat',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->alamat_domisili;
                                                    }
                                                    return $model->alamat_identitas;
                                                }
                                            ],
                                            [
                                                'label' => 'Desa',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->desa_lurah_domisili;
                                                    }
                                                    return $model->desa_lurah_identitas;
                                                }
                                            ],

                                            [
                                                'attribute' => 'RT',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->rt_domisili;
                                                    }
                                                    return $model->rt_identitas;
                                                }
                                            ],
                                            [
                                                'attribute' => 'RW',
                                                'value' => function ($model) {
                                                    if (!$model->is_current_domisili) {
                                                        return $model->rw_domisili;
                                                    }
                                                    return $model->rw_identitas;
                                                }
                                            ],
                                            [
                                                'attribute' => 'Informasi Lain',
                                                'value' => function ($model) {
                                                    return $model->informasi_lain;
                                                }
                                            ]

                                        ],
                                    ]) ?>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-6 col-md-3">
                                            <?= DetailView::widget([
                                                'model' => $model,
                                                'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                                                'attributes' => [
                                                    [
                                                        'label' => 'Ijazah Terakhir',
                                                        'value' => function ($model) {
                                                            if ($model->ijazah_terakhir != null) {
                                                                return Html::a('preview', Yii::getAlias('@root') . '/panel/' . $model->ijazah_terakhir, ['target' => '_blank']);
                                                            }
                                                            return '<p>Belum Di Set<p>';
                                                        },
                                                        'format' => 'raw',
                                                    ],

                                                ],
                                            ]) ?>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <?= DetailView::widget([
                                                'model' => $model,
                                                'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                                                'attributes' => [
                                                    [
                                                        'label' => 'CV',
                                                        'value' => function ($model) {
                                                            if ($model->ijazah_terakhir != null) {
                                                                return Html::a('preview', Yii::getAlias('@root') . '/panel/' . $model->cv, ['target' => '_blank']);
                                                            }
                                                            return '<p>Belum Di Set<p>';
                                                        },
                                                        'format' => 'raw',
                                                    ],
                                                ],

                                            ]) ?>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <?= DetailView::widget([
                                                'model' => $model,
                                                'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                                                'attributes' => [
                                                    [
                                                        'label' => 'Foto',
                                                        'value' => function ($model) {
                                                            if ($model->foto != null) {
                                                                return Html::a('preview', Yii::getAlias('@root') . '/panel/' . $model->foto, ['target' => '_blank']);
                                                            }
                                                            return '<p>Belum Di Set<p>';
                                                        },
                                                        'format' => 'raw',
                                                    ],

                                                ],
                                            ]) ?>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <?= DetailView::widget([
                                                'model' => $model,
                                                'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
                                                'attributes' => [

                                                    [
                                                        'label' => 'KTP',
                                                        'value' => function ($model) {
                                                            if ($model->ktp != null) {
                                                                return Html::a('preview', Yii::getAlias('@root') . '/panel/' . $model->ktp, ['target' => '_blank']);
                                                            }
                                                            return '<p>Belum Di Set<p>';
                                                        },
                                                        'format' => 'raw',
                                                    ],


                                                ],
                                            ]) ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div style="display: flex !important; align-items: center; justify-content: space-between ;margin-block: 20px 10px" style="gap: 10px;">
                                <p style="font-weight: 700; font-size: 20px">Data Pekerjaan</p>
                                <?= Html::a('Add new', ['/data-pekerjaan/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </div>

                            <div class="table-responsive">

                                <?= GridView::widget([
                                    'dataProvider' => $pekerjaandataProvider,
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
                                            'urlCreator' => function ($action, $model) {
                                                return Url::toRoute(['/data-pekerjaan/view/', 'id_data_pekerjaan' => $model['id_data_pekerjaan']]);
                                            }
                                        ],

                                        [
                                            'label' => 'Bagian',
                                            'value' => function ($model) {
                                                return $model['nama_bagian'];
                                            }
                                        ],


                                        [
                                            'headerOptions' => ['style' => 'text-align: center;'],
                                            'contentOptions' => ['style' => 'text-align: center;'],
                                            'label' => 'Dari',
                                            'value' => function ($model) {
                                                $tanggalFormat = new Tanggal();
                                                return $tanggalFormat->getIndonesiaFormatTanggal($model['dari']);
                                                // return date('d-m-Y', strtotime($model['dari']));
                                            }
                                        ],
                                        [
                                            'headerOptions' => ['style' => 'text-align: center;'],
                                            'contentOptions' => ['style' => 'text-align: center;'],
                                            'label' => 'Sampai',
                                            'value' => function ($model) {
                                                // dd($model);
                                                $tanggalFormat = new Tanggal();
                                                if ($model['sampai']) {
                                                    return $tanggalFormat->getIndonesiaFormatTanggal($model['sampai']);
                                                }
                                                return '';
                                                // return date('d-m-Y', strtotime($model['sampai']));
                                            }
                                        ],
                                        [
                                            'headerOptions' => ['style' => 'text-align: center;'],
                                            'contentOptions' => ['style' => 'text-align: center;'],
                                            'attribute' => 'status',
                                            'value' => function ($model) {
                                                return $model['status_pekerjaan'];
                                            }
                                        ],


                                        [
                                            'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                                            'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
                                            'label' => 'Aktif',
                                            'format' => 'html',
                                            'value' => function ($model) {
                                                return $model['is_aktif'] == 1 ? '<span class="text-success">YA</span>' : '<span class="text-danger">Tidak</span>';
                                            }
                                        ],

                                        [
                                            'headerOptions' => ['style' => 'width: 3%; text-align: center;'],
                                            'contentOptions' => ['style' => 'width: 3%; text-align: center;'],
                                            'format' => 'raw',
                                            'label' => 'Draft Kontrak',
                                            'value' => function ($model) {
                                                if ($model['is_aktif'] == 1) {
                                                    if ($model['id_cetak']) {
                                                        return Html::a('<button class="btn btn-primary"><i class="fa fa-eye"></i></button></button>', ['/cetak/view', 'id_cetak' => $model['id_cetak']]);
                                                    } else {
                                                        return Html::a('<button class="btn btn-primary"><i class="fa fa-plus"></i></button></button>', ['/cetak/create', 'id_karyawan' => $model['id_karyawan'], 'id_data_pekerjaan' => $model['id_data_pekerjaan']]);
                                                    }
                                                } else {
                                                    return '<span class=""></span>';
                                                }
                                            }
                                        ],
                                        [
                                            'headerOptions' => ['style' => 'width: 3%; text-align: center;'],
                                            'contentOptions' => ['style' => 'width: 3%; text-align: center;'],
                                            'format' => 'raw',
                                            'label' => 'Fix Kontrak',
                                            'value' => function ($model) {
                                                if ($model['is_aktif'] == 1) {
                                                    if ($model['id_cetak']) {
                                                        if ($model['surat_upload']) {
                                                            return Html::a('<button class="btn btn-warning"><i class="fas fa-edit"></i></button></button>', ['/cetak/upload', 'id_cetak' => $model['id_cetak']]);
                                                        }
                                                        return Html::a('<button class="btn btn-success"><i class="fas fa-upload"></i></button></button>', ['/cetak/upload', 'id_cetak' => $model['id_cetak']]);
                                                    }
                                                    return '<span class=""></span>';
                                                }
                                                return '<span class=""></span>';
                                            }
                                        ]

                                    ],
                                ]); ?>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/pengalaman-kerja/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </p>

                            <?= GridView::widget([
                                'dataProvider' => $pengalamankerjaProvider,
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
                                        'urlCreator' => function ($action, PengalamanKerja $model, $key, $index, $column) {
                                            return Url::toRoute(['pengalaman-kerja/view/', 'id_pengalaman_kerja' => $model->id_pengalaman_kerja]);
                                        }
                                    ],
                                    'perusahaan',
                                    'posisi',
                                    [
                                        'label' => "masuk pada",
                                        'value' => function ($model) {
                                            $tanggalFormat = new Tanggal();
                                            return $tanggalFormat->getIndonesiaFormatTanggal($model->masuk_pada);
                                        }
                                    ],
                                    [
                                        'label' => "masuk pada",
                                        'value' => function ($model) {
                                            $tanggalFormat = new Tanggal();
                                            return $tanggalFormat->getIndonesiaFormatTanggal($model->keluar_pada);
                                        }
                                    ]


                                ],
                            ]); ?>

                        </div>
                        <div class="tab-pane fade " id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/riwayat-pendidikan/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </p>


                            <?php
                            echo  GridView::widget([
                                'dataProvider' => $riwayarProvider,

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
                                        'urlCreator' => function ($action, RiwayatPendidikan $model, $key, $index, $column) {
                                            return Url::toRoute(['riwayat-pendidikan/view/', 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan]);
                                        }
                                    ],
                                    [
                                        'label' => 'Jenjang Pendidikan',
                                        'value' => function ($model) {
                                            return $model->jenjangPendidikan->nama_kode;
                                        }
                                    ],
                                    'institusi',
                                    'tahun_masuk',
                                    'tahun_keluar',


                                ],
                            ]);
                            ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/data-keluarga/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </p>


                            <?= GridView::widget([
                                'dataProvider' => $dataKeluargaProvider,
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
                                        'urlCreator' => function ($action, DataKeluarga $model, $key, $index, $column) {
                                            return Url::toRoute(['data-keluarga/view/', 'id_data_keluarga' => $model->id_data_keluarga]);
                                        }
                                    ],
                                    'nama_anggota_keluarga',
                                    [
                                        'label' => 'hubungan',
                                        'value' => function ($model) {
                                            return $model->jenisHubungan->nama_kode;
                                        }
                                    ],
                                    'pekerjaan',
                                    'tahun_lahir',

                                ],
                            ]); ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-pelatihan" role="tabpanel" aria-labelledby="custom-tabs-one-pelatihan-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/riwayat-pelatihan/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </p>


                            <?= GridView::widget([
                                'dataProvider' => $pelatihanProvider,
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
                                        'urlCreator' => function ($action, RiwayatPelatihan $model, $key, $index, $column) {
                                            return Url::toRoute(['/riwayat-pelatihan/view/', 'id_riwayat_pelatihan' => $model->id_riwayat_pelatihan]);
                                        }
                                    ],
                                    'judul_pelatihan',
                                    [
                                        'label' => "Tanggal Mulai",
                                        'value' => function ($model) {
                                            $tanggalFormat = new Tanggal();
                                            return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_mulai);
                                        }
                                    ],
                                    [
                                        'label' => "Tanggal Selesai",
                                        'value' => function ($model) {
                                            $tanggalFormat = new Tanggal();
                                            return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal_selesai);
                                        }
                                    ]
                                ],
                            ]); ?>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-kesehatan" role="tabpanel" aria-labelledby="custom-tabs-one-kesehatan-tab">

                            <p class="d-flex justify-content-end " style="gap: 10px;">
                                <?= Html::a('Add new', ['/riwayat-kesehatan/create', 'id_karyawan' => $model->id_karyawan], ['class' => 'tambah-button']) ?>
                            </p>


                            <?= GridView::widget([
                                'dataProvider' => $KesehatanProvider,
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
                                        'urlCreator' => function ($action, RiwayatKesehatan $model, $key, $index, $column) {
                                            return Url::toRoute(['/riwayat-kesehatan/view/', 'id_riwayat_kesehatan' => $model->id_riwayat_kesehatan]);
                                        }
                                    ],
                                    [
                                        'label' => 'Karyawan',
                                        'value' => function ($model) {
                                            return $model->karyawan->nama;
                                        }

                                    ],
                                    'nama_pengecekan',
                                    'keterangan:ntext',

                                ],
                            ]); ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


</div>