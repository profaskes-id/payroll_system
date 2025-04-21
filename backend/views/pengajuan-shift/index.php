<?php

use backend\models\PengajuanShift;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanShiftSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Pengajuan Shifts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-shift-index">

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
                <?php echo $this->render('_search', [
                    'model' => $searchModel,
                    // 'tgl_mulai' => $tgl_mulai,
                    // 'tgl_selesai' => $tgl_selesai
                ]); ?>
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
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
                'urlCreator' => function ($action, PengajuanShift $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_pengajuan_shift' => $model->id_pengajuan_shift]);
                }
            ],
            [
                'attribute' => 'id_karyawan',
                'label' => "Karyawan",
                'value' => function ($model) {
                    return $model->karyawan->nama;
                }
            ],
            [
                'attribute' => 'id_shift_kerja',
                'label' => "Shift Kerja",
                'value' => function ($model) {
                    return $model->shiftKerja->nama_shift;
                }
            ],
            [
                'attribute' => 'diajukan_pada',
                'label' => "Diajukan Pada",
                'value' => function ($model) {
                    $tanggal = new Tanggal();
                    return $tanggal->getIndonesiaFormatTanggal($model->diajukan_pada);
                }
            ],

            [
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
                'format' => 'raw',
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function ($model) {
                    if ($model->status !== null) {
                        if (strtolower($model->status) == 0) {
                            return "<span class='text-capitalize text-warning '>Pending</span>";
                        } elseif (strtolower($model->status) == 1) {
                            return "<span class='text-capitalize text-success '>disetujui</span>";
                        } elseif (strtolower($model->status) == 2) {
                            return "<span class='text-capitalize text-danger '>ditolak</span>";
                        }
                    } else {
                        return "<span class='text-danger'>master kode tidak aktif</span>";
                    }
                },
            ],
            //'ditanggapi_oleh',
            //'ditanggapi_pada',
            //'catatan_admin:ntext',
        ],
    ]); ?>


</div>