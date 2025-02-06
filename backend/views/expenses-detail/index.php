<?php

use backend\models\ExpensesDetail;
use backend\models\Tanggal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Biaya & Beban';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenses-detail-index">


    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="number" id="tahun" maxlength="4" min="2000" max="2100" placeholder="Input Tahun " class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="reset-button" data-dismiss="modal">Close</button>
                    <a href="/panel/expenses-detail/index/" target="_blank" id="report_link" class="add-button">Tampilkan Report Tahun : </a>
                </div>
            </div>
        </div>
    </div>


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="row">


        <div class="col-12 col-md-10">
            <button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                <i class="fas fa-search"></i>
                <span>
                    Search
                </span>
            </button>
        </div>
        <!-- <div class="col-3 col-md-2">
            <p class="d-block ">
            <?php // Html::a('Report', ['report', 'tahun' => 2024], ['target' => '_blank', 'class' => 'cetak-button text-center']) 
            ?>            </p>
        </div> -->
        <div class="col-3 col-md-2">
            <p class="d-block">
                <button type="button" class="tambah-button" data-toggle="modal" data-target="#exampleModal">
                    Show Report <i class="fa fa-table"></i>
                </button>
                <!-- <?php // Html::a('', ['report', 'tahun' => date('Y')], ['target' => '_blank', 'class' => '']) 
                        ?> -->
            </p>
        </div>
    </div>
    <div style="margin-top: 10px;">
        <div class="collapse width" id="collapseWidthExample">
            <div class="" style="width: 100%;">
                <?php echo $this->render('_search', [
                    'model' => $searchModel,
                    'tgl_mulai' => $tgl_mulai,
                    'tgl_selesai' => $tgl_selesai,
                ]); ?>
            </div>
        </div>
    </div>

</div>

<div class="table-container">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}', // Hanya tampilkan items, hilangkan pagination
        'pager' => false,
        'showFooter' => !empty($total), // Tampilkan footer hanya jika $total tidak kosong
        'footerRowOptions' => ['style' => 'font-weight:bold; background-color: #f5f5f5;'], // Styling footer

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
                'urlCreator' => function ($action,  $model, $key, $index, $column) {
                    return Url::toRoute(['/expenses-detail/view', 'id_expense_detail' => $model['id_expense_detail']]);
                }
            ],

            [
                'attribute' => 'tanggal',
                'label' => 'Tanggal & Kode',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 15%; text-align: center;'],
                'value' => function ($model) {
                    $tanggal = new Tanggal();
                    return "
                    <a href='/panel/expenses-detail/update?id_expense_header={$model['id_expense_header']}'>
                    <div class='gap-0 d-flex flex-column'>
                    <p style='font-size:11px; margin-bottom:-0px;' > 
                    {$tanggal->getIndonesiaFormatTanggal($model['tanggal'])}
                    </p>
                    <p class='fw-bold' style='  '> 
                    {$model['id_expense_header']}
                    </p>
                    </div>
                    </a>
                    ";
                }
            ],

            [
                'format' => 'raw',
                'label' => ' Kategori & Tipe Pengeluaran',
                'headerOptions' => ['style' => 'width: 30%; '],
                'value' => function ($model) {
                    return "
                     <div class='gap-0 d-flex flex-column'>
                    <p style='font-size:11px; margin-bottom:-0px;' > 
                {$model['nama_kategori']}
                    </p>
                    <p style='font-size:14px;'>
                {$model['nama_subkategori']} 
                    </p>
                    </div>";
                }
            ],

            [
                'format' => 'raw',
                'attribute' => 'jumlah',
                'value' => function ($model) {
                    return 'Rp. ' . number_format($model['jumlah'], 2, ',', '.');
                },
                'footer' => !empty($total) ? 'Total: Rp. ' . number_format($total, 2, ',', '.') : '', // Tampilkan footer hanya jika $total tidak kosong
                'footerOptions' => ['style' => 'text-align: right; font-weight: bold;'], // Styling footer
            ],

            'keterangan'
        ],
    ]); ?>
</div>


</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $('#tahun').change(function(e) {
            e.preventDefault();
            $tahun = $(this).val();
            $('#report_link').attr('href', '/panel/expenses-detail/report?tahun=' + $tahun);
            $('#report_link').html(' Tampilkan Report Tahun : ' + $tahun);
        });

        $('#tahun').keyup(function(e) {
            e.preventDefault();
            $tahun = $(this).val();
            $('#report_link').attr('href', '/panel/expenses-detail/report?tahun=' + $tahun);
            $('#report_link').html(' Tampilkan Report Tahun : ' + $tahun);
        });
    });
</script>