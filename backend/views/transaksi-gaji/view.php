<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;


// ============================================================================
// HELPER FUNCTIONS
// ============================================================================
$trashSvg = '
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
     viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v7a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0V6z"/>
  <path fill-rule="evenodd"
        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1 0-2H5.5l1-1h3l1 1H14a1 1 0 0 1 .5 1z"/>
</svg>';

// Function helper untuk format tanggal


function formatTanggal($date)
{
    if (empty($date) || $date == '0000-00-00') return '-';
    return date('d M Y', strtotime($date));
}

// Function helper untuk format waktu
function formatWaktu($time)
{
    if (empty($time) || $time == '00:00:00') return '-';
    return $time;
}

// Function helper untuk format currency tanpa ,00
function formatRupiah($amount)
{
    if (!isset($amount) || $amount === '' || $amount === null) return 'Rp 0';
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
function safeValue($value)
{
    return isset($value) && $value !== '' ? $value : '-';
}

function formatBulanIndonesia($bulan)
{
    $bulanIndo = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    return $bulanIndo[$bulan] ?? '-';
}



// ============================================================================
// PAGE SETUP
// ============================================================================

// Membuat title dengan pengecekan null
$nama = isset($data['nama']) ? $data['nama'] : 'Unknown';
$bulan = isset($data['bulan']) ? $data['bulan'] : date('n');
$tahun = isset($data['tahun']) ? $data['tahun'] : date('Y');
$title = $nama . ' - ' . date('F Y', strtotime($tahun . '-' . $bulan . '-01'));

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Gaji', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// ============================================================================
// GET QUERY PARAMETERS & SET MODEL VALUES
// ============================================================================

$request = Yii::$app->request;

$idKaryawan = $request->get('id_karyawan');
$bulan = $request->get('bulan');
$tahun = $request->get('tahun');
$pendapatan = $request->get('pendapatan');
$potongan = $request->get('potongan');

// Set default values based on URL parameters
if ($idKaryawan) {
    $model->id_karyawan = $idKaryawan;
}
if ($bulan) {
    $model->bulan = $bulan;
}
if ($tahun) {
    $model->tahun = $tahun;
}

// Default to pendapatan if not specified
if ($pendapatan === null && $potongan === null) {
    $model->is_pendapatan = 1;
    $model->is_potongan = 0;
} else {
    if ($potongan !== null) {
        $model->is_potongan = $potongan;
        $model->is_pendapatan = $potongan ? 0 : 1;
    }
    if ($pendapatan !== null) {
        $model->is_pendapatan = $pendapatan;
        $model->is_potongan = $pendapatan ? 0 : 1;
    }
}

// Initialize arrays for multiple inputs
$jumlahItems = $model->jumlah ? (is_array($model->jumlah) ? $model->jumlah : [$model->jumlah]) : [''];
$keteranganItems = $model->keterangan ? (is_array($model->keterangan) ? $model->keterangan : [$model->keterangan]) : [''];

// If arrays don't have same length, adjust them
$maxCount = max(count($jumlahItems), count($keteranganItems));
while (count($jumlahItems) < $maxCount) $jumlahItems[] = '';
while (count($keteranganItems) < $maxCount) $keteranganItems[] = '';

?>

<?= $this->render('_modal_view', compact('model', 'jumlahItems', 'keteranganItems')); ?>

<div class="costume-container">
    <p class="">
        <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
    </p>
</div>


<!-- ============================================================================ -->
<!-- DETAIL VIEW SECTION -->
<!-- ============================================================================ -->
<div class="table-container">

    <div class="row">
        <div class="col-12 col-lg-6">

            <button type="button"
                style="background-color:#63cc48;"
                class="mb-2 tambah-button"
                id="btn-pendapatan"
                data-bs-toggle="modal"
                data-bs-target="#modal-form">
                <i class="fas fa-plus-circle"></i> Tambah Pendapatan Lainnya
            </button>


            <?= GridView::widget([
                'dataProvider' => $dataPendapatanProvider,
                'tableOptions' => ['class' => 'table table-bordered table-striped'],
                'summary' => false,
                'columns' => [
                    [
                        'headerOptions' => ['style' => 'background-color: #63cc40; color: #fff; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'class' => 'yii\grid\SerialColumn'
                    ],
                    [
                        'format' => 'raw',
                        'label' => 'Aksi',
                        'headerOptions' => ['style' => 'background-color: #63cc40; color: #fff; width:70px'],
                        'value' => function ($model) use ($trashSvg) {
                            return Html::a(
                                $trashSvg,
                                ['pendapatan-potongan-lainnya/delete', 'id_ppl' => $model->id_ppl],
                                [
                                    'class' => 'reset-button',
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => 'Yakin ingin menghapus data ini?',
                                    ],
                                    'title' => 'Hapus',
                                ]
                            );
                        }
                    ],

                    [
                        'headerOptions' => ['style' => 'background-color: #63cc40; color: #fff;'],
                        'label' => 'Keterangan',
                        'value' => function ($model) {
                            return $model->keterangan;
                        }
                    ],
                    [
                        'headerOptions' => ['style' => 'background-color: #63cc40; color: #fff;'],
                        'label' => 'Jenis',
                        'value' => function ($model) {
                            return $model->is_pendapatan == 1 ? 'Pendapatan' : 'Potongan';
                        }
                    ],
                    [
                        'headerOptions' => ['style' => 'background-color: #63cc40; color: #fff;'],
                        'label' => 'Jumlah',
                        'value' => fn($model) => formatRupiah($model->jumlah ?? 0),
                    ],

                ],
            ]) ?>
        </div>
        <div class="col-12 col-lg-6">
            <button type="button"
                class="mb-2 reset-button"
                id="btn-potongan"
                data-bs-toggle="modal"
                data-bs-target="#modal-form">
                <i class="fas fa-minus-circle"></i> Tambah Potongan Lainnya
            </button>
            <?= GridView::widget([
                'dataProvider' => $dataPotonganProvider,
                'tableOptions' => ['class' => 'table table-bordered table-striped'],
                'summary' => false,

                'columns' => [
                    [
                        'headerOptions' => ['style' => 'background-color: #ec484b; color: #fff; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'class' => 'yii\grid\SerialColumn'

                    ],
                    [
                        'label' => 'Aksi',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'background-color: #ec484b; color: #fff; width:70px'],
                        'value' => function ($model) use ($trashSvg) {
                            return Html::a(
                                $trashSvg,
                                ['pendapatan-potongan-lainnya/delete', 'id_ppl' => $model->id_ppl],
                                [
                                    'class' => 'reset-button',
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => 'Yakin ingin menghapus data ini?',
                                    ],
                                    'title' => 'Hapus',
                                ]
                            );
                        }

                    ],
                    [
                        'headerOptions' => ['style' => 'background-color: #ec484b; color: #fff;'],
                        'label' => 'Keterangan',
                        'value' => function ($model) {
                            return $model->keterangan;
                        }
                    ],
                    [
                        'headerOptions' => ['style' => 'background-color: #ec484b; color: #fff;'],
                        'label' => 'Jenis',
                        'value' => function ($model) {
                            return $model->is_potongan == 1 ? "Potongan" :  'Pendapatan';
                        }
                    ],
                    [
                        'headerOptions' => ['style' => 'background-color: #ec484b; color: #fff;'],
                        'label' => 'Jumlah',
                        'value' => fn($model) => formatRupiah($model->jumlah ?? 0),
                    ],

                ],
            ]) ?>
        </div>
    </div>
</div>