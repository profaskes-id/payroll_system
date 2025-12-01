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


                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>

                        <?= Html::a(
                            '<i class="fas fa-edit"></i>',
                            ['/master-kode/view', 'nama_group' => Yii::$app->params['tanggal-cut-of'], 'kode' => $tanggal_cut_of['kode'] ?? ''], // Update with your actual route
                            [
                                'class' => 'btn btn-sm btn-primary btn-edit', // Added btn-edit class
                                'title' => 'Edit',
                                'target' => '_blank'
                            ]
                        ) ?>
                    </td>
                    <td><?= Html::encode($tanggal_cut_of['nama_group']) ?></td>
                    <td><?= Html::encode($tanggal_cut_of['nama_kode']) ?></td>
                    <td class="text-capitalize">Tanggal dimulai perhitungan penggajian dengan menginputkan (tanggal)</td>


                </tr>

                <tr>
                    <td>

                        <?= Html::a(
                            '<i class="fas fa-edit"></i>',
                            ['/master-kode/view', 'nama_group' => Yii::$app->params['potongan-persen-wfh'], 'kode' => $potongan_persenan_wfh['kode'] ?? ''],
                            [
                                'class' => 'btn btn-sm btn-primary btn-edit ',
                                'title' => 'Edit',
                                'target' => '_blank',
                            ]
                        ) ?>
                    </td>
                    <td><?= Html::encode($potongan_persenan_wfh['nama_group']) ?></td>
                    <td><?= Html::encode($potongan_persenan_wfh['nama_kode']) ?></td>
                    <td style="text-transform: capitalize;">(%) Potongan Persenan jika karyawan WFH</td>


                </tr>

                <tr>
                    <td>

                        <?= Html::a(
                            '<i class="fas fa-edit"></i>',
                            ['/master-kode/view', 'nama_group' => Yii::$app->params['toleransi-keterlambatan'], 'kode' => $toleransi_keterlambatan['kode'] ?? ''],
                            [
                                'class' => 'btn btn-sm btn-primary btn-edit ',
                                'title' => 'Edit',
                                'target' => '_blank'
                            ]
                        ) ?>
                    </td>
                    <td><?= Html::encode($toleransi_keterlambatan['nama_group']) ?></td>
                    <td><?= Html::encode($toleransi_keterlambatan['nama_kode']) ?></td>
                    <td style="text-transform: capitalize;">(Menit) nilai minimal toleransi keterlambatan, diatasnya akan dilakukan pemotongan gaji</td>


                </tr>
                <tr>
                    <td>

                        <?= Html::a(
                            '<i class="fas fa-edit"></i>',
                            ['/master-kode/view', 'nama_group' => Yii::$app->params['batas-deviasi-absensi'], 'kode' => $batas_deviasi_absensi['kode'] ?? ''],
                            [
                                'class' => 'btn btn-sm btn-primary btn-edit ',
                                'title' => 'Edit',
                                'target' => '_blank'
                            ]
                        ) ?>
                    </td>
                    <td><?= Html::encode($batas_deviasi_absensi['nama_group']) ?></td>
                    <td><?= Html::encode($batas_deviasi_absensi['nama_kode']) ?></td>
                    <td style="text-transform: capitalize;">(hari) minimal batas deviasi absensi, diatasnya tidak akan dilakukan pemotongan gaji</td>


                </tr>
            </tbody>
        </table>
    </div>
</div>