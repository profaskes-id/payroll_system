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
                'tanggal_mulai',
                'tanggal_selesai',
                //'estimasi_biaya',
                //'biaya_yang_disetujui',
                //'disetujui_oleh',
                //'disetujui_pada',
                [
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Pending</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success">Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    }
                ]
            ],
        ]); ?>
    </div>


</div>