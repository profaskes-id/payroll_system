<?php

use backend\models\PengajuanKasbon;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanKasbonSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengajuan Kasbon';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-kasbon-index">

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

    <div class="table-container">
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
                    'urlCreator' => function ($action, PengajuanKasbon $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_pengajuan_kasbon' => $model->id_pengajuan_kasbon]);
                    }
                ],


                [
                    'label' => 'Nama',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                'jumlah_kasbon',
                'tanggal_pengajuan',

                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function ($model) {
                        if ($model->status == 0) {
                            return '<span class="text-warning">Pending</span>';
                        } elseif ($model->status == 1) {
                            return '<span class="text-success"> Disetujui</span>';
                        } elseif ($model->status == 2) {
                            return '<span class="text-danger">  Ditolak</span>';
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
            ],
        ]); ?>
    </div>
</div>