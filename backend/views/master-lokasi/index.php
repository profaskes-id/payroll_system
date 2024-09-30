<?php

use backend\models\MasterLokasi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MasterLokasiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Master Lokasis');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-lokasi-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Master Lokasi'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_master_lokasi',
            'label',
            'alamat',
            'longtitude',
            'latitude',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterLokasi $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_master_lokasi' => $model->id_master_lokasi]);
                 }
            ],
        ],
    ]); ?>


</div>
