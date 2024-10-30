<?php

use backend\models\TunjanganDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tunjangan Details');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tunjangan Detail'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tunjangan_detail',
            'id_tunjangan',
            'id_karyawan',
            'jumlah',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TunjanganDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_tunjangan_detail' => $model->id_tunjangan_detail]);
                 }
            ],
        ],
    ]); ?>


</div>
