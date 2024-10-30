<?php

use backend\models\GajiPotongan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\GajiPotonganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Gaji Potongans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-potongan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Gaji Potongan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_gaji_potongan',
            'id_potongan_detail',
            'nama_potongan',
            'jumlah',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, GajiPotongan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_gaji_potongan' => $model->id_gaji_potongan]);
                 }
            ],
        ],
    ]); ?>


</div>
