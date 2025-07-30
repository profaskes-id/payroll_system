<?php

use backend\models\PengajuanTugasLuar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanTugasLuarSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Pengajuan Tugas Luars');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-tugas-luar-index">

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
                    'urlCreator' => function ($action, PengajuanTugasLuar $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_tugas_luar' => $model->id_tugas_luar]);
                    }
                ],
                [
                    'attribute' => 'karyawan',
                    'label' => "Karyawan",
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'format' => 'raw',
                    'attribute' => 'status_pengajuan',
                    'label' => 'Status',
                    'value' => function ($model) {
                        if ($model->status_pengajuan !== null) {
                            if (strtolower($model->status_pengajuan) == 0) {
                                return "<span class='text-capitalize text-warning '>Pending</span>";
                            } elseif (strtolower($model->status_pengajuan) == 1) {
                                return "<span class='text-capitalize text-success '>disetujui</span>";
                            } elseif (strtolower($model->status_pengajuan) == 2) {
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