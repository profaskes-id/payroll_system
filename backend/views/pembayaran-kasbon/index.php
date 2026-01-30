<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PembayaranKasbonSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pembayaran Kasbon';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pembayaran-kasbon-index">


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

    <div class="table-container">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['style' => 'width:5%; text-align:center;'],
                    'contentOptions' => ['style' => 'width:5%; text-align:center;'],
                ],
                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width:5%; text-align:center;'],
                    'class' => ActionColumn::class,
                    'urlCreator' => function ($action, $model) {
                        return Url::toRoute(['view', 'id_karyawan' => $model->id_karyawan]);
                    }
                ],

                // ✅ Nama karyawan
                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama ?? '(tidak ada data karyawan)';
                    }
                ],

                // ✅ Data pekerjaan aktif
                [
                    'label' => 'Pekerjaan & jabatan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        // Aman dari null
                        $karyawan = $model->karyawan ?? null;
                        if (!$karyawan || empty($karyawan->dataPekerjaans)) {
                            return '<span style="color:gray;">Tidak ada data pekerjaan</span>';
                        }

                        // Ambil pekerjaan aktif
                        $aktif = null;
                        foreach ($karyawan->dataPekerjaans as $dp) {
                            if ((int)$dp->is_aktif === 1) {
                                $aktif = $dp;
                                break;
                            }
                        }

                        if (!$aktif) {
                            return '<span style="color:gray;">Tidak ada pekerjaan aktif</span>';
                        }

                        // Pastikan relasi bagian aman
                        $bagian = $aktif->bagian->nama_bagian ?? '(bagian tidak diketahui)';

                        // Cari nama jabatan dari tabel MasterKode
                        $jabatan = backend\models\MasterKode::find()
                            ->where(['nama_group' => 'jabatan', 'status' => 1,])
                            ->select('nama_kode')
                            ->scalar();

                        $jabatan = $jabatan ?: '(jabatan tidak diketahui)';

                        return " <span>{$bagian}</span><br><hr class='p-0 ' style='margin: 3px 0px;'> <span>{$jabatan}</span>";
                    }
                ],

                // ✅ Format Rupiah
                [
                    'label' => 'Jumlah Kasbon',
                    'format' => 'raw',
                    'value' => fn($model) => 'Rp ' . number_format((float)$model->jumlah_kasbon, 0, ',', '.')
                ],
                [
                    'attribute' => 'sisa_kasbon',
                    'label' => 'Sisa Kasbon',
                    'format' => 'raw',
                    'value' => fn($model) => 'Rp ' . number_format((float)$model->sisa_kasbon, 0, ',', '.')
                ],
            ],
        ]); ?>
    </div>

</div>