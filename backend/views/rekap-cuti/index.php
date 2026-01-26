<?php

use backend\models\DataPekerjaan;
use backend\models\RekapCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap4\Modal;


/** @var yii\web\View $this */
/** @var backend\models\RekapCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Rekap Cuti Karyawan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-cuti-index">


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
                    'attribute' => 'Karyawan',

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
                    'attribute' => 'Tahun',


                    'value' => function ($model) {
                        return $model['tahun'] ?? "-";
                    }
                ],
                [
                    'label' => "Hak Cuti ",
                    'value' => function ($model) {
                        return $model['jatah_hari_cuti'] ?? 'belum di set';
                    }
                ],




                [
                    'label' => 'Total Cuti Digunakan',
                    'format' => 'raw',
                    'value' => function ($model) {


                        return Html::a(
                            ($model['total_hari_terpakai'] ?? 0) . ' Hari',
                            '#',
                            [
                                'class' => 'show-cuti-modal',
                                'data-toggle' => 'modal',
                                'data-target' => '#cutiModal',
                                'data-id-karyawan' => $model['id_karyawan'],
                                'data-id-master-cuti' => $model['id_master_cuti'] ?? 1,
                                'style' => 'cursor:pointer;',
                            ]
                        );
                    }
                ],


            ],
        ]); ?>


    </div>
</div>


<?php

Modal::begin([
    'id' => 'cutiModal',
    'title' => 'Detail Pengajuan Cuti',
    'size' => Modal::SIZE_LARGE,
]);

echo '<div id="modalContent">Silahkan klik "Total Cuti Digunakan".</div>';

Modal::end();
?>




<?php

$tahun = $searchModel->tahun ?? date('Y');
$viewUrl = \yii\helpers\Url::to(['rekap-cuti/view-pengajuan-cuti']);
$js = <<<JS
$('#cutiModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Tombol yang memicu modal
    var idKaryawan = button.data('id-karyawan');
    var idMasterCuti = button.data('id-master-cuti');

    var modal = $(this);
    modal.find('#modalContent').html('Loading...');

    $.ajax({
        url: '$viewUrl',
        type: 'GET',
        data: {
            id_karyawan: idKaryawan,
            id_master_cuti: idMasterCuti,
            tahun: $tahun
        },
        success: function(data) {
            modal.find('#modalContent').html(data);
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
            modal.find('#modalContent').html('<p>Terjadi kesalahan saat memuat data.</p>');
        }
    });
});
JS;

$this->registerJs($js);

?>