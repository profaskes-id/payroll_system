<?php

use backend\models\JamKerja;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerjaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$months = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
];
$this->title = Yii::t('app', 'Total Hari Kerja');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="total-hari-kerja-index">


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
                    'urlCreator' => function ($action, JamKerja $model, $key, $index, $column) {
                        return Url::toRoute(['/total-hari-kerja/view', 'id_jam_kerja' => $model->id_jam_kerja, 'jenis_shift' => $model->jenis_shift]);
                    }
                ],
                'nama_jam_kerja',
                [
                    'label' => 'Jenis Shift',
                    'value' => function ($model) {
                        return $model->jenisShift->nama_kode;
                    }
                ],

            ],
        ]); ?>
    </div>




</div>