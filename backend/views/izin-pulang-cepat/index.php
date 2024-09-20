<?php

use backend\models\IzinPulangCepat;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\IzinPulangCepatSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Izin Pulang Cepat');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-pulang-cepat-index">



    <!-- <div class="costume-container">
        <p class="">
            <?php // Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) 
            ?>
        </p>
    </div> -->

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
                    'urlCreator' => function ($action, IzinPulangCepat $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]);
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'headerOptions' => ['style' => ' text-align: center;'],
                    'contentOptions' => ['style' => ' text-align: center;'],
                    'label' => 'Tanggal',
                    'value' => function ($model) {
                        return date('d-m-Y', strtotime($model->tanggal));
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
</div>