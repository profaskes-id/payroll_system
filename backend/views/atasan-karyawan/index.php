<?php

use amnah\yii2\user\models\Profile;
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
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Setting Atasan', ['create'], ['class' => 'costume-btn']) ?>
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


    <div class="table-container table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, AtasanKaryawan $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_atasan_karyawan' => $model->id_atasan_karyawan]);
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->karyawan->nama ?? "";
                    }
                ],
                [

                    'label' => 'Jabatan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (!$model->karyawan) {
                            return "";
                        }

                        $pekerjaanAktif = array_filter($model->karyawan->dataPekerjaans, function ($pekerjaan) {
                            return $pekerjaan->is_aktif == 1;
                        });

                        if (empty($pekerjaanAktif)) {
                            return "";
                        }

                        // Ambil pekerjaan aktif pertama (asumsi hanya ada satu yang aktif)
                        $pekerjaan = reset($pekerjaanAktif);

                        // Dapatkan relasi jabatan
                        $jabatan = $pekerjaan->jabatanPekerja;

                        return $jabatan ? $jabatan->nama_kode : "";
                    }
                ],

              

                [
                    'format' => 'raw',
                    'label' => 'Atasan',
                    'value' => function ($model) {
                        return $model->atasan->nama;
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
                            return $atasan->masterLokasi->label . ' (' . $atasan->masterLokasi->nama_lokasi . ')';
                        }
                        return '<p class="text-danger">(Belum Di Set)</p>';
                    }
                ],


            ],
        ]); ?>
    </div>



</div>