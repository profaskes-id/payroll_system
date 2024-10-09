<?php

use backend\models\Cetak;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\CetakSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Cetaks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cetak-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cetak'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_cetak',
            'id_karyawan',
            'id_data_pekerjaan',
            'nomor_surat',
            'nama_penanda_tangan',
            //'jabatan_penanda_tangan',
            //'deskripsi_perusahaan:ntext',
            //'status',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Cetak $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_cetak' => $model->id_cetak]);
                 }
            ],
        ],
    ]); ?>


</div>
