<?php

use backend\models\RiwayatPelatihan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RiwayatPelatihanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Riwayat Pelatihan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pelatihan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Riwayat Pelatihan'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_karyawan',
            'judul_pelatihan',
            'tanggal_mulai',
            'tanggal_selesai',
            //'penyelenggara',
            //'deskripsi:ntext',
            //'sertifikat',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, RiwayatPelatihan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_riwayat_pelatihan' => $model->id_riwayat_pelatihan]);
                }
            ],
        ],
    ]); ?>


</div>