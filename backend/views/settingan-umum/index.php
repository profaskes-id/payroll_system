<?php

use backend\models\SettinganUmum;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\SettinganUmumSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Settingan Umum');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingan-umum-index">
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
                    'urlCreator' => function ($action, SettinganUmum $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_settingan_umum' => $model->id_settingan_umum]);
                    }
                ],
                'kode_setting',
                'nama_setting',
                'ket',
                [
                    'attribute' => 'nilai_setting',
                    'value' => function ($model) {
                        return $model->nilai_setting == 1 ? "Aktif" : "Tidak Aktif";
                    },
                ]
            ],
        ]); ?>

    </div>
</div>