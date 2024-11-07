<?php

use backend\models\PeriodeGaji;
use backend\models\Tanggal;
use PhpParser\Node\Stmt\Return_;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PeriodeGajiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Periode Gaji');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="periode-gaji-index">

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
                    'class' => 'yii\grid\Column',
                    'header' => 'Aksi',
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'content' => function ($model, $key, $index, $column) {
                        return "<div class='d-flex '>" .
                            Html::a(
                                '<i class="fas fa-edit"></i>', // Icon tong sampah (menggunakan Font Awesome)
                                ['update', 'bulan' => $model->bulan, 'tahun' => $model->tahun],
                                [
                                    'class' => 'edit-button mr-2',
                                ]
                            ) .
                            Html::a(
                                '<i class="fas fa-trash"></i>', // Icon tong sampah (menggunakan Font Awesome)
                                ['delete', 'bulan' => $model->bulan, 'tahun' => $model->tahun],
                                [
                                    'class' => 'hapus-button',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]
                            ) .
                            "</div>";
                    },
                ],
                [
                    'label' => 'Bulan',
                    'value' => function ($model) {
                        $tanggal = new Tanggal();
                        return $tanggal->getBulan($model->bulan);
                    }
                ],
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'label' => 'Tahun',
                    'value' => function ($model) {
                        return $model->tahun;
                    }
                ],
                [
                    'label' => 'Tanggal Awal',
                    'value' => function ($model) {
                        $tanggal = new Tanggal();
                        return $tanggal->getIndonesiaFormatTanggal($model->tanggal_awal);
                    }
                ],
                [
                    'label' => 'Tanggal Akhir',
                    'value' => function ($model) {
                        $tanggal = new Tanggal();
                        return $tanggal->getIndonesiaFormatTanggal($model->tanggal_akhir);
                    }
                ],
                [
                    'label' => 'Terima',
                    'value' => function ($model) {
                        $tanggal = new Tanggal();
                        return $tanggal->getIndonesiaFormatTanggal($model->terima);
                    }
                ],
            ],
        ]); ?>


    </div>
</div>