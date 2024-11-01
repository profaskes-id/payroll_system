<?php

use backend\models\GajiTunjangan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjanganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Gaji Tunjangan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-tunjangan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Gaji Tunjangan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'urlCreator' => function ($action, GajiTunjangan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_gaji_tunjangan' => $model->id_gaji_tunjangan]);
                }
            ],

            'id_gaji_tunjangan',
            'id_tunjangan_detail',
            'nama_tunjangan',
            'jumlah',
        ],
    ]); ?>


</div>