<?php

use backend\models\RekapCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RekapCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Cutis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-cuti-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rekap Cuti', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_rekap_cuti',
            'id_master_cuti',
            'id_karyawan',
            'total_hari_terpakai',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, RekapCuti $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_rekap_cuti' => $model->id_rekap_cuti]);
                 }
            ],
        ],
    ]); ?>


</div>
