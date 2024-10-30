<?php

use backend\models\GajiTunjangan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\GajiTunjanganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Gaji Tunjangans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-tunjangan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Gaji Tunjangan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_gaji_tunjangan',
            'id_tunjangan_detail',
            'nama_tunjangan',
            'jumlah',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, GajiTunjangan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_gaji_tunjangan' => $model->id_gaji_tunjangan]);
                 }
            ],
        ],
    ]); ?>


</div>
