<?php

// use backend\models\HariLibur;
use backend\models\JadwalKerja;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\JamKerja $model */

$this->title = $model->nama_jam_kerja;
$this->params['breadcrumbs'][] = ['label' => 'Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jam-kerja-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">


        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

            <p class="d-flex justify-content-end " style="gap: 10px;">
                <?= Html::a('Update', ['update', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'id_jam_kerja' => $model->id_jam_kerja], [
                    'class' => 'reset-button',
                    'data' => [
                        'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'nama_jam_kerja',
                    [
                        'label' => 'Jenis Shift',
                        'value' => function ($model) {
                            return $model->jenisShift->nama_kode;
                        }
                    ],
                ],
            ]) ?>


            <br>
            <div class="d-flex align-items-center justify-content-between">
                <h4>Jadwal Kerja</h4>
                <p class="d-flex justify-content-end " style="gap: 10px;">
                    <?= Html::a('Add new', ['/jadwal-kerja/create', 'id_jam_kerja' => $model->id_jam_kerja], ['class' => 'tambah-button']) ?>
                </p>
            </div>
            <?= GridView::widget([
                'dataProvider' => $jadwalKerjaProvider,
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
                        'urlCreator' => function ($action, JadwalKerja $model, $key, $index, $column) {
                            return Url::toRoute(['jadwal-kerja/view', 'id_jadwal_kerja' => $model->id_jadwal_kerja]);
                        }
                    ],
                    [
                        'label' => 'Nama Hari',
                        'value' => function ($model) {
                            return $model->getNamaHari($model->nama_hari);
                        },
                    ],
                    'jam_masuk',
                    'jam_keluar',

                ],
            ]); ?>
        </div>
    </div>
</div>