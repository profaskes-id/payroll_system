<?php

use backend\models\PeriodeGaji;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGajiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Periode Gajis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periode-gaji-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Periode Gaji'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bulan',
            'tahun',
            'tanggal_awal',
            'tanggal_akhir',
            'terima',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PeriodeGaji $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'bulan' => $model->bulan, 'tahun' => $model->tahun]);
                 }
            ],
        ],
    ]); ?>


</div>
