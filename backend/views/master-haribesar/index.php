<?php

use backend\models\MasterHaribesar;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MasterHaribesarSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Master Hari Besar');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-haribesar-index">


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
                    'urlCreator' => function ($action, MasterHaribesar $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'kode' => $model->kode]);
                    }
                ],
                [
                    'headerOptions' => ['text-align: center;'],
                    'contentOptions' => ['text-align: center;'],
                    'attribute' => 'Tanggal',
                    'value' => function ($model) {
                        $tanggalFormat = new Tanggal();
                        return $tanggalFormat->getIndonesiaFormatTanggal($model->tanggal);
                        // return date('d-M-Y', strtotime($model->tanggal));
                    }
                ],
                'nama_hari:ntext',
                [
                    'attribute' => 'libur_nasional',
                    'value' => function ($model) {
                        return $model->libur_nasional ? 'Yes' : 'No';
                    }
                ]
            ],
        ]); ?>

    </div>

</div>