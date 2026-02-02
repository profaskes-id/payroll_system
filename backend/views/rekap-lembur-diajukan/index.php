<?php

use backend\models\Karyawan;
use backend\models\RekapLembur;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RekapLemburSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Pengajuan Lembur';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-lembur-index">





    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?= $this->render('_search', ['model' => $model]);
                ?>
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
                    'format' => 'raw',
                    'value' => function ($model) {
                        // Ambil params aman dari null
                        $params = Yii::$app->request->get('DynamicModel', []);
                        $tglMulai  = $params['tgl_mulai'] ?? '';
                        $tglSelesai = $params['tgl_selesai'] ?? '';

                        return Html::a(
                            '<button style="border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset; display: block;">
                <span style="margin: 3px 3px !important; display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                        <path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/>
                    </svg>
                </span>
            </button>',
                            [
                                '/pengajuan-lembur/index',
                                'id_karyawan' => $model['id_karyawan'],
                                'tanggal_mulai' => $tglMulai,
                                'tanggal_selesai' => $tglSelesai
                            ],
                            ['target' => '_blank'] // buka di tab baru
                        );
                    }
                ],

                // 'kode_karyawan',
                'nama',
                'bagian',
                'jabatan',

                [
                    'attribute' => 'total_pengajuan',
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Total Pengajuan',
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'attribute' => 'total_belum_disetujui',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Belum Ditanggapi',
                ],
                [
                    'attribute' => 'total_jam_lembur',
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Total Jam Lembur',
                    'format' => ['decimal', 1],
                ],
            ],
        ]); ?>


    </div>
</div>