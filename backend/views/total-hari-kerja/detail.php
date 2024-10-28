<?php

use backend\models\JamKerja;
use backend\models\TotalHariKerja;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\TotalHariKerja $model */


$months = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
];

$this->title =  $model->jamKerja->nama_jam_kerja . ' (' . $model->jamKerja->jenisShift->nama_kode . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Total Hari Kerja'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="total-hari-kerja-view">


    <div class="costume-container">
        <p class="">
            <?php

            $jamKerja = JamKerja::find()->where(['id_jam_kerja' => $model->id_jam_kerja])->one();
            if ($jamKerja == null) {
                Yii::$app->session->setFlash('error', 'Jam Kerja Tidak Ditemukan');
                return $this->redirect(['index']);
            }

            ?>
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/total-hari-kerja/view', 'id_jam_kerja' => $jamKerja->id_jam_kerja, 'jenis_shift' => $jamKerja->jenis_shift], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('update', ['update', 'id_total_hari_kerja' => $model->id_total_hari_kerja], ['class' => 'add-button'])
            ?>
            <?= Html::a('Delete', ['delete', 'id_total_hari_kerja' => $model->id_total_hari_kerja], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'Total Hari',
                    'value' => function ($model) {
                        return $model['total_hari'] . ' Hari Kerja';
                    }
                ],
                [
                    'attribute' => 'Keterangan',
                    'value' => function ($model) {
                        return $model['keterangan'] ?? "-";
                    }
                ],
                [
                    'attribute' => 'bulan',
                    'value' => function ($model) use ($months) {
                        return $months[$model['bulan'] - 1];
                    }
                ],
                [
                    'attribute' => 'tahun',
                    'value' => function ($model) {
                        return $model['tahun'];
                    }
                ],

                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model['is_aktif'] == 1) {
                            return "<span class='text-success'>Aktif</span>";
                        } else {
                            return "<span class='text-danger'>Tidak Aktif</span>";
                        }
                    }
                ],
            ],
        ]) ?>

    </div>
</div>