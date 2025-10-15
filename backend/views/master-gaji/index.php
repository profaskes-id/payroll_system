<?php

use backend\models\MasterGaji;
use backend\models\Terbilang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MasterGajiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Master Gaji');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-gaji-index">
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
                    'format' => 'raw',
                    'value' => function ($model) {

                        // Sesuaikan dengan data yang ada - jika belum ada transaksi gaji
                        if (isset($model['id_gaji']) && $model['id_gaji'] != null) {
                            return Html::a('<button style="border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset; display: block;">
                        <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
                        </span>
                        </button>', ['update', 'id_gaji' => $model['id_gaji']]);
                        } else {
                            // Jika belum ada transaksi, buat baru
                            return Html::a('<button style="border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset; display: block;">
                        <span style="margin: 3px 3px !important;display: block; background: #E9EC48 !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844l2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565l6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/></svg>
                        </span>
                        </button>', ['create', 'id_karyawan' => $model['id_karyawan']]);
                        }
                    }
                ],


                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model['nama'];
                    },
                ],

                [
                    'label' => 'Jabatan',
                    'value' => function ($model) {
                        return $model['jabatan'] ?? '-';
                    },
                ],

                [
                    'label' => 'Gaji',
                    'format' => ['currency'],
                    'value' => function ($model) {
                        return $model['nominal_gaji'];
                    },
                    'contentOptions' => ['style' => 'text-align: left;'],
                ],

                [
                    'label' => 'Terbilang',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model['nominal_gaji'] == null) {
                            return '-';
                        }
                        return Terbilang::toTerbilang($model['nominal_gaji'] ?? '0') . ' Rupiah';
                    }
                ],
            ],
        ]); ?>

    </div>

</div>