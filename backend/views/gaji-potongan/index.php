<?php

use backend\models\GajiPotongan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\GajiPotonganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Potongan Gaji');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gaji-potongan-index">

    <!-- 
    <div class="costume-container">
        <p class="">
            <?php // Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) 
            ?>
        </p>
    </div> -->

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
                    'class' => 'yii\grid\Column',
                    'header' => 'Delete',
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'text-align: center;'],
                    'content' => function ($model, $key, $index, $column) {
                        return Html::a(
                            '<i class="fas fa-trash"></i>', // Icon tong sampah (menggunakan Font Awesome)
                            ['delete', 'id_gaji_potongan' => $model->id_gaji_potongan],
                            [
                                'class' => 'hapus-button',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]
                        );
                    },
                ],

                [
                    'label' => 'Transaksi Gaji',
                    'value' => function ($model) {
                        return $model->transaksiGaji->nama ?? 'Not Found';
                        // $model->potonganDetail->potongan->nama_potongan);
                    }
                ],
                // 'id_potongan_detail',
                [
                    'label' => 'Potongan',
                    'value' => function ($model) {
                        // dd($model->potonganDetail );
                        return $model->potonganDetail->potongan->nama_potongan ?? 'Not Found';
                    }
                ],
                // 'nama_potongan',
                [
                    'attribute' => 'jumlah',
                    'value' => function ($model) {
                        return 'Rp. ' . number_format($model->jumlah, 2, ',', '.');
                    },
                ],
            ],
        ]); ?>


    </div>
</div>