<?php

use backend\models\DataPekerjaan;
use backend\models\JatahCutiKaryawan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\JatahCutiKaryawanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Jatah Cuti Karyawan';
$this->params['breadcrumbs'][] = $this->title;
$tahun = Yii::$app->request->get('JatahCutiKaryawanSearch')['tahun'] ?? date('Y');

?>
<div class="jatah-cuti-karyawan-index">

    <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
        <i class="fas fa-search"></i>
        <span>
            Search
        </span>
    </button>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', ['model' => $searchModel, 'tahun' => $tahun]); ?>
            </div>
        </div>
    </div>

    <div class="table-container">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'columns' => [
                [
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'format' => 'raw',
                    'value' => function ($model) use ($tahun) {
                        if ($model['id_jatah_cuti']) {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #488aec50 !important; color:#252525; all:unset;  display: block;">
                                            <span style="margin: 3px 3px !important;display: block; background: #488aec !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0"/></svg>
                                            </span>
                                            </button>', ['view', 'id_jatah_cuti' => $model['id_jatah_cuti'],],);
                        } else {
                            return Html::a('<button  style=" border-radius: 6px !important; background: #E9EC4850 !important; color:#252525; all:unset;  display: block;">
                                            <span style="margin: 3px 3px !important;display: block; background: #E9EC48 !important; padding: 0px 3px 0.1px !important; border-radius: 6px !important;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844l2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565l6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/></svg>
                                            </span>
                                            </button>', ['create', 'id_karyawan' => $model['id_karyawan'], 'tahun' => $tahun],);
                        }
                    }
                ],


                [
                    'label' => 'Karyawan',
                    'value' => function ($model) {
                        return $model['nama'];
                    }

                ],

                [
                    'attribute' => 'Masa Kerja',

                    'value' => function ($model) {
                        $statusdataPekerjaan = DataPekerjaan::findAll(['id_karyawan' => $model['id_karyawan'], 'is_aktif' => 1]);
                        if ($statusdataPekerjaan == null) {
                            return "";
                        }
                        $dataPekerjaan = DataPekerjaan::findAll(['status' => $statusdataPekerjaan, 'id_karyawan' => $model['id_karyawan']]);


                        $dates = array_map(function ($dataPekerjaan) {
                            return $dataPekerjaan->dari;
                        }, $dataPekerjaan);





                        $earliestDate = min($dates);


                        $startDate = new DateTime($earliestDate);
                        $endDate = new DateTime(); // Tanggal hari ini

                        $interval = $startDate->diff($endDate);

                        $years = $interval->y;
                        $months = $interval->m;
                        $days = $interval->d;

                        // Format durasi masa kerja
                        $duration = '';
                        if ($years > 0) {
                            $duration .= $years . ' tahun ';
                        }
                        if ($months > 0) {
                            $duration .= $months . ' bulan ';
                        }
                        if ($days > 0) {
                            $duration .= $days . ' hari';
                        }

                        return $duration ?: 'Kurang dari 1 hari';
                    }
                ],
                [
                    'label' => 'tahun',
                    'value' => function ($model) {

                        return $model['tahun'];
                    }
                ],
                'jatah_hari_cuti',

            ],
        ]); ?>
    </div>



</div>