<?php

use backend\models\JadwalKerja;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JadwalKerjaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Jadwal kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-kerja-index">

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

    <div class='table-container'>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],

                'id_jam_kerja',
                'nama_hari',
                'jam_masuk',
                'jam_keluar',
                //'lama_istirahat',
                //'jumlah_jam',
                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, JadwalKerja $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_jadwal_kerja' => $model->id_jadwal_kerja]);
                    }
                ],
            ],
        ]); ?>
    </div>


</div>