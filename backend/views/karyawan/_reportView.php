<?php

use yii\grid\GridView;

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Karyawan $model */

$this->title = 'Report Data Karyawan';
?>

<div class="karyawan-view">
    <div>
        <h3>Data personal</h3>
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
                        'nomer_identitas',
                        [

                            'label' => 'Jenis Identitas',
                            'value' => function ($model) {
                                return $model->jenisidentitas->nama_kode ?? '';
                            }
                        ],
                        [
                            'label' => 'Jenis Kelamin',
                            'value' => function ($model) {
                                return $model->kode_jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan';
                            },
                        ],


                        [
                            'label' => 'Tanggal Lahir',
                            'value' => function ($model) {
                                return date('d-M-Y', strtotime($model->tanggal_lahir));
                            }
                        ],
                        [
                            'attribute' => 'nomer_telepon',
                            'label' => 'Nomer Telepon',
                        ],
                        [
                            'attribute' => 'email',
                            'label' => 'Email',
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
                <h4 style="margin-top: 10px;">Alamat Sekarang</h4>
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

        </div>
        <br>

        <h3>Data Pekerjaan</h3>
        <?= GridView::widget([
            'dataProvider' => $pekerjaandataProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],

                [
                    'label' => 'Bagian',
                    'value' => function ($model) {
                        return $model->bagian->nama_bagian;
                    }
                ],
                [
                    'label' => 'Bagian',
                    'value' => function ($model) {
                        return $model->jabatanPekerja->nama_kode;
                    }
                ],

                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Dari',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->dari));
                    }
                ],
                [
                    'attribute' => 'Sampai',
                    'value' => function ($model) {
                        if ($model->is_currenty != null) {
                            return 'Sekarang';
                        }
                        return date('d-m-Y', strtotime($model->sampai));
                    }
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Status',
                    'value' => function ($model) {
                        return $model->statusPekerjaan->nama_kode;
                    }
                ],
                [
                    'label' => 'Gaji Pokok',
                    'value' => function ($model) {

                        // Set locale to Indonesian
                        $locale = 'id_ID';
                        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);

                        // Format the number to IDR
                        $amount = (int)$model->gaji_pokok;
                        return $fmt->formatCurrency($amount, 'IDR'); // Output: Rp2.800.000,00

                    }
                ],


            ],
        ]); ?>


        <h3>Pengalaman Kerja</h3>
        <?= GridView::widget([
            'dataProvider' => $pengalamankerjaProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],

                [
                    'label' => 'Perusahaan',
                    'value' => 'perusahaan'
                ],
                [
                    'label' => 'Posisi',
                    'value' => 'posisi'
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Masuk Pada',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->masuk_pada));
                    }
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Keluar Pada',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->keluar_pada));
                    }
                ],

            ],
        ]); ?>



        <h3>Riwayat Pendidikan</h3>
        <?php
        echo  GridView::widget([
            'dataProvider' => $riwayarProvider,
            'summary' => false,

            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'label' => 'Jenjang Pendidikan',
                    'value' => function ($model) {
                        return $model->jenjangPendidikan->nama_kode;
                    }
                ],
                [
                    'label' => 'Institusi',
                    'value' => 'institusi'
                ],

                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tahun Masuk',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tahun_masuk));
                    }
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tahun Keluar',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tahun_keluar));
                    }
                ],
            ],
        ]);
        ?>



        <h3>Data Keluarga</h3>
        <?= GridView::widget([
            'dataProvider' => $dataKeluargaProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'label' => 'Nama',
                    'value' => 'nama_anggota_keluarga'
                ],
                [
                    'label' => 'hubungan',
                    'value' => function ($model) {
                        return $model->jenisHubungan->nama_kode;
                    }
                ],
                [
                    'label' => 'pekerjaan',
                    'value' => 'pekerjaan'
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'tahun_ lahir',
                    'value' => 'tahun_lahir'
                ],

            ],
        ]); ?>


        <h3>Riwayat Pelatihan</h3>
        <?= GridView::widget([
            'dataProvider' => $pelatihanProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'label' => 'Judul Pelatihan',
                    'value' => 'judul_pelatihan'
                ],
                [
                    'label' => 'Penyelenggara',
                    'value' => 'penyelenggara'
                ],

                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tanggal Mulai',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal_mulai));
                    }
                ],

                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tanggal Selesai',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal_selesai));
                    }
                ],
            ],
        ]); ?>



        <h3>Riwayat Pengecekan Kesehatan</h3>
        <?= GridView::widget([
            'dataProvider' => $KesehatanProvider,
            'summary' => false,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],

                [
                    'label' => 'Nama Pengecekan',
                    'value' => 'nama_pengecekan'
                ],
                [
                    'label' => 'Keterangan',
                    'value' => 'keterangan'
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tanggal',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal));
                    }
                ],

            ],
        ]); ?>
    </div>
</div>