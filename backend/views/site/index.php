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


<section class="mt-6 grid grid-cols-1 md:grid-cols-12 justify-center gap-4">
    <div class="col-span-8">
        <div class="grid grid-cols-1 gap-y-3">
            <div class="grid grid-cols-2 gap-2 px-2 place-items-center  p-1 rounded-md">
                <div class="w-full h-[150px] rounded-md p-5 bg-white ">
                    <div class="flex flex-col items-start justify-center">
                        <p class="font-bold text-2xl"><?= $TotalKaryawan ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                        <p class="text-sm text-gray-500">Total Karyawan</p>
                    </div>
                </div>
                <div class="w-full h-[150px] rounded-md p-5 text-white bg-[#131133]/75">
                    <div class="flex flex-col items-start justify-center">
                        <p class="font-bold text-2xl"><?= $TotalData ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                        <p class="text-sm text-gray-500">Hadir Hari Ini</p>
                    </div>
                </div>
                <div class="w-full h-[150px] rounded-md p-5 text-white bg-[#131133]/75">
                    <div class="flex flex-col items-start justify-center">
                        <p class="font-bold text-2xl"><?= $TotalDataBelum ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                        <p class="text-sm text-gray-500">Tanpa Keterangan</p>
                    </div>
                </div>
                <div class="w-full h-[150px] rounded-md p-5 bg-white ">
                    <div class="flex flex-col items-start justify-center">
                        <p class="font-bold text-2xl"><?= $TotalIzin ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                        <p class="text-sm text-gray-500">Izin Berhalangan Hadir</p>
                    </div>
                </div>
                <!-- <div class="w-full h-[150px] bg-teal-500"></div> -->
            </div>

            <h1 class="text-bold text-3xl  mt-10">Pengajuan Karyawan</h1>
            <p class="-mt-2 text-gray-500 mb-3">Menunggu Ditanggapi</p>
            <div class='table-container'>

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
            </div>
        </div>
    </div>
    <div class="col-span-4">
        <div class="grid grid-col-1 gap-y-3 table-container">
            <div class="h-[400px] w-full bg-gray-500"></div>
            <div class="h-[400px] w-full bg-gray-400">
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
            </div>
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