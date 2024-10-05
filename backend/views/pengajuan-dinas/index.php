<?php

use backend\models\PengajuanDinas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengajuan Dinas Luar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-dinas-index">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <?php Pjax::begin(); ?>
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
                [
                    'label' => 'Keterangan Perjalanan',
                    'value' => function ($model) {
                        $text = $model->keterangan_perjalanan;
                        $words = explode(' ', $text);
                        if (count($words) > 8) {
                            $text = implode(' ', array_slice($words, 0, 8)) . '...';
                        }

                        return $text;
                    }
                ],


                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Tanggal Mulai',
                    'format' => 'date',
                    'value' => function ($model) {
                        return $model->tanggal_mulai;
                    }
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Estimasi Biaya',
                    'value' => function ($model) {
                        return $model->estimasi_biaya;
                    }
                ],
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

    <?php Pjax::end(); ?>

</div>