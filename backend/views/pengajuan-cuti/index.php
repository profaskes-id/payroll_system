<?php

use backend\models\PengajuanCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengajuan Cuti';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(); ?>

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
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
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
                'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PengajuanCuti $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
                }
            ],
            [
                'label' => 'Nama',
                'value' => function ($model) {
                    return $model->karyawan->nama;
                }
            ],
            [
                'attribute' => 'tanggal_mulai',
                'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 20%; text-align: center;'],
            ],
            [
                'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 20%; text-align: center;'],
                'label' => 'Tanggal Selesai',
                'value' => function ($model) {
                    return date('d-m-Y', strtotime($model->tanggal_selesai));
                }
            ],
            [
                'headerOptions' => ['style' => ' text-align: center;'],
                'contentOptions' => ['style' => ' text-align: center;'],
                'label' => 'Jenis Cuti',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->jenisCuti->jenis_cuti;
                },
            ],
            // 'tanggal_selesai',
            [
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
                'format' => 'raw',
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->statusPengajuan->nama_kode !== null) {
                        if (strtolower($model->statusPengajuan->nama_kode) == "pending") {
                            return "<span class='text-capitalize btn btn-outline-warning '>Pending</span>";
                        } elseif (strtolower($model->statusPengajuan->nama_kode) == "disetujui") {
                            return "<span class='text-capitalize btn btn-outline-success '>Disetujui</span>";
                        } elseif (strtolower($model->statusPengajuan->nama_kode) == "ditolak") {
                            return "<span class='text-capitalize btn btn-outline-danger '>ditolak</span>";
                        }
                    } else {
                        return "<span class='text-danger'>master kode tidak aktif</span>";
                    }
                },
            ],
        ],
    ]); ?>
</div>

<?php Pjax::end(); ?>

</div>