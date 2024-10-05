<?php

use backend\models\RiwayatPendidikan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPendidikanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Riwayat pendidikan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pendidikan-index">

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
                // 'id_riwayat_pendidikan',
                'id_karyawan',
                'jenjang_pendidikan',
                'institusi',
                // 'tahun_masuk',
                // 'tahun_keluar',
                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, RiwayatPendidikan $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_riwayat_pendidikan' => $model->id_riwayat_pendidikan]);
                    }
                ],
            ],
        ]); ?>
    </div>


</div>