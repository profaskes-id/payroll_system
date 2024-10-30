<?php

use backend\models\Potongan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PotonganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Potongans');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="potongan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Potongan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_potongan',
            'nama_potongan',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Potongan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_potongan' => $model->id_potongan]);
                 }
            ],
        ],
    ]); ?>


</div>
