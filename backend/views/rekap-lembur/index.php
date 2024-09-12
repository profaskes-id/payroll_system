<?php

use backend\models\PengajuanLembur;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RekapLemburSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Pengajuan Lembur');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-lembur-index">


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
                    'urlCreator' => function ($action, PengajuanLembur $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
                    }
                ],
                [
                    'label' => 'karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'jam_mulai',
                'jam_selesai',
                'tanggal',
                [
                    'label' => 'Status Lembur',
                    'value' => function ($model) {
                        return $model->statusPengajuan->nama_kode;
                    }
                ]
                //'disetujui_oleh',
                //'disetujui_pada',

            ],
        ]); ?>


    </div>
</div>