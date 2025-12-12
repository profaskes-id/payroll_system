<?php

use backend\models\PengajuanDinas;
// use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanDinasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengajuan Dinas Luar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajuan-dinas-index">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <?php Pjax::begin(); ?>
    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', [
                    'model' => $searchModel,
                    'tgl_mulai' => $tgl_mulai,
                    'tgl_selesai' => $tgl_selesai
                ]); ?>
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
                    'urlCreator' => function ($action, PengajuanDinas $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
                    }
                ],
                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model->karyawan->nama;
                    }
                ],
                [
                    'label' => 'Keterangan Perjalanan',
                    'value' => function ($model) {
                        return $model->keterangan_perjalanan;;
                    }
                ],



                [

                    'label' => 'Biaya Diajukan',
                    'value' => function ($model) {
                        return $model->estimasi_biaya;
                    },
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],
                [
                    'label' => 'Biaya Disetujui',
                    'value' => function ($model) {
                        return $model->biaya_yang_disetujui;
                    },
                    'format' => 'currency', // Format currency untuk otomatis
                    'contentOptions' => ['style' => 'text-align: left;'], // Align text ke kanan
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'format' => 'raw',
                    'label' => 'Status',
                    'value' => function ($model) {
                        if ($model->statusPengajuan->nama_kode !== null) {
                            if (strtolower($model->statusPengajuan->nama_kode) == "pending") {
                                return "<span class='text-capitalize text-warning '>Pending</span>";
                            } elseif (strtolower($model->statusPengajuan->nama_kode) == "disetujui") {
                                return "<span class='text-capitalize text-success '>disetujui</span>";
                            } elseif (strtolower($model->statusPengajuan->nama_kode) == "ditolak") {
                                return "<span class='text-capitalize text-danger '>ditolak</span>";
                            }
                        } else {
                            return "<span class='text-danger'>master kode tidak aktif</span>";
                        }
                    },
                ],
                [
                    'headerOptions' => ['style' => 'text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'format' => 'raw',
                    'label' => 'Status Dibayarkan',
                    'value' => function ($model) {
                        if ($model->status_dibayar == 1) {
                            return Html::button('Lunas', [
                                'class' => 'btn btn-success btn-sm',
                                'disabled' => true,
                                'title' => 'Sudah dibayarkan',
                            ]);
                        } else {
                            return Html::beginTag('form', [
                                'method' => 'post',
                                'action' => Url::to(['pengajuan-dinas/bayarkan', 'id' => $model->id_pengajuan_dinas]),
                                'style' => 'display:inline-block;',
                            ]) .
                                Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) . // manual CSRF
                                Html::submitButton('Belum Dibayar', [
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Klik untuk tandai sudah dibayar',
                                    'onclick' => "return confirm('Yakin ingin menandai sebagai sudah dibayarkan?')"
                                ]) .
                                Html::endTag('form');
                        }
                    },
                ],



            ],
        ]); ?>
    </div>

    <?php Pjax::end(); ?>

</div>