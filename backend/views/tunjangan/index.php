<?php

use backend\models\Tunjangan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TunjanganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tunjangans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tunjangan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tunjangan',
            'nama_tunjangan',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Tunjangan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_tunjangan' => $model->id_tunjangan]);
                 }
            ],
        ],
    ]); ?>


</div>
