<?php

use backend\models\PengajuanLembur;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanLemburSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengajuan Lembur';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-lembur-index">



    <?php Pjax::begin(); ?>


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
                "label" => "Karyawan",
                "value" => "karyawan.nama"
            ],
            [
                'label' => 'Jam Mulai',
                'value' => 'jam_mulai',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'label' => 'Tanggal',
                'value' => 'tanggal',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],

            [

                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['style' => 'text-align: center;'],
                'format' => 'raw',
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusPengajuan->nama_kode ?? "<span class='text-danger'>master kode tidak aktif</span>";
                },
            ],            //'jam_selesai',
            //'disetujui_oleh',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>