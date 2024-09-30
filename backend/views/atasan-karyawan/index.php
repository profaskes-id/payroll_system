<?php

use backend\models\AtasanKaryawan;
use backend\models\Karyawan;
use backend\models\MasterKode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\AtasanKaryawanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Atasan Karyawan');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-karyawan-index">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?= $this->render('_search', ['model' => $searchModel]);
                ?>
            </div>
        </div>
    </div>


    <div class='table-container'>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],

                [
                    'label' => 'Karyawan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model['id_atasan'] != null) {
                            return Html::a($model['nama'], Url::to(['atasan-karyawan/view', 'id_karyawan' => $model['id_karyawan']]));
                        } else {
                            return Html::a($model['nama'], Url::to(['atasan-karyawan/create', 'id_karyawan' => $model['id_karyawan']]));
                        }
                    }
                ],
                [
                    'label' => 'Atasan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (!$model) {
                            return '<p class="text-danger">(Belum Di Set)</p>';
                        }
                        $data = Karyawan::find()->where(['id_karyawan' => $model['id_atasan']])->select(['nama'])->one();
                        return $data->nama ?? '<p class="text-danger">(Belum Di Set)</p>';
                    }
                ],
                [
                    'label' => 'Status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (!$model) {
                            return '<p class="text-danger">(Belum Di Set)</p>';
                        }
                        if ($model['status'] == 0) {
                            return '<p class="text-danger">Tidak Aktif</p>';
                        } else {
                            return '<p class="text-success">Aktif</p>';
                        }
                    }
                ],
                [
                    'label' => 'Penampatan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (!$model) {
                            return '<p class="text-danger">(Belum Di Set)</p>';
                        }
                        $atasan = AtasanKaryawan::find()->where(['id_master_lokasi' => $model['id_master_lokasi']])->one();
                        if ($atasan) {
                            return $atasan->masterLokasi->label;
                        }
                        return '<p class="text-danger">(Belum Di Set)</p>';
                    }
                ],
            ],
        ]); ?>
    </div>



</div>