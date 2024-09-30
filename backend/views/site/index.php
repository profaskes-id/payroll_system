<?php

use backend\models\IzinPulangCepat;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Hello, ' . Yii::$app->user->identity->username ?? 'admin';

?>

<link href="<?= Yii::getAlias('@root') . '/css/tailwind_output.css' ?>" rel="stylesheet">
<style>
    h2 {
        color: #252525;
        font-size: 30px;
        text-transform: capitalize;
        margin-bottom: 20px;
    }

    th {
        background-color: #fff;
        padding: 8px;
        color: #262626;
    }

    td {
        padding: 8px;
        border-bottom: gray;
    }

    @media screen and (min-width: 768px) {
        h2 {
            font-size: 35px;
        }
    }
</style>

<div class="flex justify-between items-center mb-5">
    <p class="text-gray-500 font-medium ">Lihat Rekapan Absensi Hari Ini</p>
    <div class="flex space-x-2 items-center -mt-10">

        <?php
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $bulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        $tanggal = date('l, d F Y');
        $tanggal = str_replace(array_keys($hari), array_values($hari), $tanggal);
        $tanggal = str_replace(array_keys($bulan), array_values($bulan), $tanggal);
        echo "<p>$tanggal</p>";
        ?>


        <div class="grid place-items-center w-12 h-12 bg-gray-200 rounded-full ">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                <g fill="none">
                    <path stroke="black" stroke-width="1.5" d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14z" />
                    <path stroke="black" stroke-linecap="round" stroke-width="1.5" d="M7 4V2.5M17 4V2.5M2.5 9h19" opacity="0.5" />
                    <path fill="black" d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
                </g>
            </svg>
        </div>

    </div>
</div>
<section class="mt-6 grid grid-cols-1 md:grid-cols-12 justify-center gap-4">
    <div class="col-span-8">
        <div class="grid grid-cols-1 gap-y-3">
            <div class="grid grid-cols-2 gap-2 px-2 place-items-center  p-1 rounded-md">
                <div class="w-full h-[150px] rounded-md p-5 bg-white ">
                    <div class="flex items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/users.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalKaryawan ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                            <p class="text-base text-gray-500">Total Karyawan</p>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[150px] rounded-md p-5 text-white bg-sky-500">
                    <div class="flex  items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/hadir.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalData ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                            <p class="text-base text-white">Hadir Hari Ini</p>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[150px] rounded-md p-5 text-white bg-sky-500">
                    <div class="flex  items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/tanpa-keterangan.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalDataBelum ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                            <p class="text-base text-white">Tanpa Keterangan</p>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[150px] rounded-md p-5 bg-white ">
                    <div class="flex  items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/izin.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalIzin ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                            <p class="text-base text-gray-500">Izin Berhalangan Hadir</p>
                        </div>
                    </div>
                </div>
                <!-- <div class="w-full h-[150px] bg-teal-500"></div> -->
            </div>


            <div class='table-container'>

                <div class=" w-full bg-white">
                    <p class="text-bold mt-0">Total Hadir Karyawan Minggu Ini</p>
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-4">
        <div class="grid grid-col-1 gap-y-3 table-container">

            <h1 class="text-bold mt-0" style="font-size:22px;">Pengajuan Karyawan</h1>
            <p class="-mt-2 text-gray-500 mb-3">Menunggu Ditanggapi</p>
            <div class="grid grid-cols-6 gap-10">
                <div class="col-span-6">
                    <h1 class="font-semibold text-lg mb-2">Pengajuan Izin Pulang Cepat</h1>

                    <?= GridView::widget([
                        'dataProvider' => $PulangCepat_dataProvider,
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
                                'urlCreator' => function ($action, IzinPulangCepat $model, $key, $index, $column) {
                                    return Url::toRoute(['izin-pulang-cepat/view', 'id_izin_pulang_cepat' => $model->id_izin_pulang_cepat]);
                                }
                            ],
                            [
                                'label' => 'Karyawan',
                                'value' => function ($model) {
                                    return $model->karyawan->nama;
                                }
                            ],

                        ],
                    ]); ?>
                </div>
                <div class="col-span-6">
                    <h1 class="font-semibold text-lg mb-2">Pengajuan Cuti</h1>
                    <?= GridView::widget([
                        'dataProvider' => $PengajuanCuti_dataProvider,
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
                                    return Url::toRoute(['pengajuan-cuti/view', 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
                                }
                            ],
                            [
                                'label' => 'Nama',
                                'value' => function ($model) {
                                    return $model->karyawan->nama;
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

                        ],
                    ]); ?>

                </div>
                <div class="col-span-6">
                    <h1 class="font-semibold text-lg mb-2">Pengajuan Lembur</h1>
                    <?= GridView::widget([
                        'dataProvider' => $PengajuanLembu_dataProvider,
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
                                'urlCreator' => function ($action, PengajuanLembur $model, $key, $index, $column) {
                                    return Url::toRoute(['pengajuan-lembur/view', 'id_pengajuan_lembur' => $model->id_pengajuan_lembur]);
                                }
                            ],
                            [
                                "label" => "Nama",
                                "value" => "karyawan.nama"
                            ],

                            [
                                'label' => 'Tanggal',
                                'value' => function ($model) {
                                    return date('d-M-Y', strtotime($model->tanggal));
                                },
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                            ],


                        ],
                    ]); ?>


                </div>
                <div class="col-span-6">
                    <h1 class="font-semibold text-lg mb-2">Pengajuan Dinas</h1>
                    <?= GridView::widget([
                        'dataProvider' => $PengajuanDinas_dataProvider,
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
                                    return Url::toRoute(['pengajuan-dinas/view', 'id_pengajuan_dinas' => $model->id_pengajuan_dinas]);
                                }
                            ],
                            [
                                'label' => 'Karyawan',
                                'value' => function ($model) {
                                    return $model->karyawan->nama;
                                }
                            ],


                            [
                                'headerOptions' => ['style' => 'text-align: center;'],
                                'contentOptions' => ['style' => 'text-align: center;'],
                                'label' => 'Tanggal Mulai',
                                'format' => 'date',
                                'value' => function ($model) {
                                    return $model->tanggal_mulai;
                                }
                            ],

                        ],
                    ]); ?>


                </div>

            </div>
            <!-- <div class="h-[400px] w-full bg-gray-400">
                <div class="max-w-md mx-auto p-4 pt-6 md:p-6 lg:p-12">
                    <div class="flex justify-center mb-4">
                        <h2 id="month-year"></h2>
                    </div>
                    <div class="overflow-hidden rounded-lg shadow-md">
                        <table id="calendar-table" class="w-full">
                            <thead id="calendar-head">
                                <tr>


                                    <th>Sun</th>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body" class="bg-white w-[300px] "></tbody>
                        </table>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

</section>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        const date = new Date();
        const month = date.getMonth();
        const year = date.getFullYear();
        const daysInMonth = getDaysInMonth(month, year);
        const firstDayOfWeek = getDayOfWeek(1, month, year);

        $('#month-year').text(`${getMonthName(month)} ${year}`);

        // Create thead section
        const $thead = $('<thead>');
        const $theadRow = $('<tr>');
        for (let i = 0; i < 7; i++) {
            const $th = $('<th>');
            $th.text(getDayName(i));
            $th.appendTo($theadRow);
        }
        $theadRow.appendTo($thead);
        $thead.appendTo($('#calendar'));

        // Create tbody section
        let dayCount = 0;
        let $row = $('<tr>'); // Create a new table row

        // Tambahkan sel kosong untuk hari-hari sebelum tanggal 1
        for (let i = 0; i < firstDayOfWeek; i++) {
            const $cell = $('<td>');
            $cell.appendTo($row);
            dayCount++;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const day = i;
            const isToday = day === date.getDate();

            const $cell = $('<td>');
            $cell.text(day);
            if (isToday) {
                $cell.addClass('today');
                $cell.html(`<span class="bg-[#f97316] rounded-full  flex px-2 -translate-x-1 p-1">${day}</span>`); // Add purple circle around the current day
            }

            $cell.appendTo($row); // Append cell to the current row
            dayCount++;

            if (dayCount === 7) {
                $row.appendTo($('#calendar-body')); // Append row to the table body
                $row = $('<tr>'); // Reset the row
                dayCount = 0;
            }
        }

        if (dayCount > 0) {
            $row.appendTo($('#calendar-body')); // Append the last row
        }

        function getDaysInMonth(month, year) {
            return new Date(year, month + 1, 0).getDate();
        }

        function getMonthName(month) {
            const months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            return months[month];
        }

        function getDayOfWeek(day, month, year) {
            const date = new Date(year, month, day);
            return date.getDay();
        }

        function getDayName(dayOfWeek) {
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            return days[dayOfWeek];
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let dates = <?= $datesAsJson ?>;

    let tanggal = [];
    let totalData = [];
    for (const key in dates) {
        tanggal.push(key)
        totalData.push({
            x: key,
            y: dates[key]
        })
    }

    let finalData = [];

    totalData.forEach(element => {
        let data = {
            x: element.x,
            y: 0
        }
        if (element.y == null)
            data.y = 0;
        else {
            data.y = Object.keys(element.y).length
        }
        finalData.push(data)
    })



    console.info(finalData);

    var options = {
        chart: {
            type: 'bar'
        },
        xaxis: {
            categories: [...tanggal]
        },
        series: [{
            'name': 'Jumlah',
            data: [...finalData]

        }],

    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
</script>

<footer>
    <p class="text-center py-2 table-container mt-5">Copyright 2024 © PT Profaskes Softech Indonesia. All Rights Reserved</p>
</footer>