<?php

use backend\models\PotonganDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PotonganDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Potongan Details');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="potongan-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Potongan Detail'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_potongan_detail',
            'id_potongan',
            'id_karyawan',
            'jumlah',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PotonganDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_potongan_detail' => $model->id_potongan_detail]);
                 }
            ],
        ],
    ]); ?>


</div>
