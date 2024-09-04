<?php

use backend\models\MasterCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MasterCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Cutis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-cuti-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Cuti', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_master_cuti',
            'jenis_cuti',
            'deskripsi_singkat:ntext',
            'total_hari_pertahun',
            'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterCuti $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_master_cuti' => $model->id_master_cuti]);
                 }
            ],
        ],
    ]); ?>


</div>
