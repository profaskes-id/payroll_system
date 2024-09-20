<?php

use backend\models\PengajuanDinas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RekapDinasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Pengajuan Dinas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-dinas-index">


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


    <div class='table-container'>
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
                    'urlCreator' => function ($action, PengajuanDinas $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'keterangan_perjalanan:ntext',
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
                //'estimasi_biaya',
                //'biaya_yang_disetujui',
                //'disetujui_oleh',
                //'disetujui_pada',
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'format' => 'raw',
                    'label' => 'Status',
                    'value' => function ($model) {
                        if ($model->statusPengajuan->nama_kode !== null) {
                            if (strtolower($model->statusPengajuan->nama_kode) == "pending") {
                                return "<span class='text-capitalize text-warning '>Pending</span>";
                            } elseif (strtolower($model->statusPengajuan->nama_kode) == "disetujui") {
                                return "<span class='text-capitalize text-success '>disetujui</span>";
                            } elseif (strtolower($model->statusPengajuan->nama_kode) == "ditolak") {
                                return "<span class='text-capitalize text-danger '>ditolak</span>";
                            }
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
            ],
        ]); ?>
    </div>


</div>