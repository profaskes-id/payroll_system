<?php

use backend\models\IzinPulangCepat;
use backend\models\PengajuanCuti;
use backend\models\PengajuanDinas;
use backend\models\PengajuanLembur;
use backend\models\Tanggal;
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
    <div class="flex space-x-2 items-center">

        <?php
        $tanggal = new Tanggal();
        $result = $tanggal->getIndonesiaFormatLong(date('l, d F Y'));
        ?>
        <p><?= $result ?></p>


        <div class="grid place-items-center w-12 h-12 bg-gray-200 rounded-full ">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                <g fill="none">
                    <path stroke="#353535" stroke-width="1.5" d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14z" />
                    <path stroke="#353535" stroke-linecap="round" stroke-width="1.5" d="M7 4V2.5M17 4V2.5M2.5 9h19" opacity="0.5" />
                    <path fill="#353535" d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
                </g>
            </svg>
        </div>

    </div>
</div>


<section class="mt-6 grid grid-cols-1 lg:grid-cols-12 justify-center  lg:gap-4">
    <div class="col-span-8">

        <div class="grid grid-cols-1 gap-y-3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 px-2 place-items-center  p-1 rounded-md">
                <div class="w-full h-[80px] rounded-md p-2 bg-white ">
                    <div class="flex items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/users.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalKaryawan ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                            <p class="text-base text-gray-500">Total Karyawan</p>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[80px] rounded-md p-2 text-white bg-sky-500">
                    <div class="flex  items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/hadir.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalData ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                            <p class="text-base text-white">Hadir Hari Ini</p>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[80px] rounded-md p-2 text-white bg-sky-500">
                    <div class="flex  items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/tanpa-keterangan.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalDataBelum ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                            <p class="text-base text-white">Tanpa Keterangan</p>
                        </div>
                    </div>
                </div>
                <div class="w-full h-[80px] rounded-md p-2 bg-white ">
                    <div class="flex  items-start justify-start space-x-5">
                        <?= Html::img('@root/images/icons/izin.svg', ["alt" => 'users', 'class' => 'w-[50px] h-[50px]']) ?>
                        <div>
                            <p class="font-bold text-3xl"><?= $TotalIzin ?> <span class="font-normal text-gray-500 text-base">Orang</span></p>
                            <p class="text-base text-gray-500">Izin Berhalangan Hadir</p>
                        </div>
                    </div>
                </div>
                <!-- <div class="w-full h-[80px] bg-teal-500"></div> -->
            </div>


            <div class="table-container table-responsive">

                <div class=" w-full bg-white ">
                    <p class="text-bold mt-0">Total Hadir Karyawan Minggu Ini</p>
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-4 py-5">
        <div class="grid grid-col-1 gap-y-3 table-container">
            <h1 class="text-bold mt-0" style="font-size:22px;">Pengajuan Karyawan</h1>
            <p class="-mt-2 text-gray-500 mb-3">Menunggu Ditanggapi</p>
            <div class="grid grid-cols-6 gap-10">
                <div class="col-span-6">
                    <div class="w-full h-[80px] rounded-md p-2 text-white bg-teal-400/70">
                        <div class="flex  items-start justify-start space-x-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24">
                                <path fill="white" d="M9.052 4.5C9 5.078 9 5.804 9 6.722v10.556c0 .918 0 1.644.052 2.222H8c-2.357 0-3.536 0-4.268-.732C3 18.035 3 16.857 3 14.5v-5c0-2.357 0-3.536.732-4.268S5.643 4.5 8 4.5z" opacity="0.5" />
                                <path fill="white" fill-rule="evenodd" d="M9.707 2.409C9 3.036 9 4.183 9 6.476v11.048c0 2.293 0 3.44.707 4.067s1.788.439 3.95.062l2.33-.406c2.394-.418 3.591-.627 4.302-1.505c.711-.879.711-2.149.711-4.69V8.948c0-2.54 0-3.81-.71-4.689c-.712-.878-1.91-1.087-4.304-1.504l-2.328-.407c-2.162-.377-3.243-.565-3.95.062m3.043 8.545c0-.434-.336-.785-.75-.785s-.75.351-.75.784v2.094c0 .433.336.784.75.784s.75-.351.75-.784z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold text-3xl"><?= $pengajuanPulangCepat ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                                <p class="text-base text-white">Pengajuan WFH</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-span-6">
                    <div class="w-full h-[80px] rounded-md p-2 text-white bg-teal-400/70">
                        <div class="flex  items-start justify-start space-x-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24">
                                <path fill="white" d="M9.052 4.5C9 5.078 9 5.804 9 6.722v10.556c0 .918 0 1.644.052 2.222H8c-2.357 0-3.536 0-4.268-.732C3 18.035 3 16.857 3 14.5v-5c0-2.357 0-3.536.732-4.268S5.643 4.5 8 4.5z" opacity="0.5" />
                                <path fill="white" fill-rule="evenodd" d="M9.707 2.409C9 3.036 9 4.183 9 6.476v11.048c0 2.293 0 3.44.707 4.067s1.788.439 3.95.062l2.33-.406c2.394-.418 3.591-.627 4.302-1.505c.711-.879.711-2.149.711-4.69V8.948c0-2.54 0-3.81-.71-4.689c-.712-.878-1.91-1.087-4.304-1.504l-2.328-.407c-2.162-.377-3.243-.565-3.95.062m3.043 8.545c0-.434-.336-.785-.75-.785s-.75.351-.75.784v2.094c0 .433.336.784.75.784s.75-.351.75-.784z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold text-3xl"><?= $pengajuanPulangCepat ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                                <p class="text-base text-white">Pengajuan Pulang Cepat</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-span-6">
                    <div class="w-full h-[80px] rounded-md p-2 text-white bg-emerald-500/60">
                        <div class="flex  items-start justify-start space-x-5">

                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                                <path fill="white" fill-rule="evenodd" d="M4.172 3.172C3 4.343 3 6.229 3 10v4c0 3.771 0 5.657 1.172 6.828S7.229 22 11 22h2c3.771 0 5.657 0 6.828-1.172S21 17.771 21 14v-4c0-3.771 0-5.657-1.172-6.828S16.771 2 13 2h-2C7.229 2 5.343 2 4.172 3.172M8 9.25a.75.75 0 0 0 0 1.5h8a.75.75 0 0 0 0-1.5zm0 4a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold text-3xl"><?= $pengajuanCuti ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                                <p class="text-base text-white">Pengajuan Cuti</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-6">
                    <div class="w-full h-[80px] rounded-md p-2 text-white bg-rose-500/70">
                        <div class="flex  items-start justify-start space-x-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                                <path fill="white" fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12m11-5a1 1 0 1 0-2 0v3.764a3 3 0 0 0 1.658 2.683l2.895 1.447a1 1 0 1 0 .894-1.788l-2.894-1.448a1 1 0 0 1-.553-.894z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-bold text-3xl"><?= $pengajuanLembur ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                                <p class="text-base text-white">Pengajuan Lembur</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-span-6">
                    <div class="w-full h-[80px] rounded-md p-2 text-white bg-orange-500/80">
                        <div class="flex  items-start justify-start space-x-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                                <path fill="white" d="M3 21v-2h18v2zm1.75-5L1 9.75l2.4-.65l2.8 2.35l3.5-.925l-5.175-6.9l2.9-.775L14.9 9.125l4.25-1.15q.8-.225 1.513.187t.937 1.213t-.187 1.513t-1.213.937z" />
                            </svg>
                            <div>
                                <p class="font-bold text-3xl"><?= $pengajuanDinas ?> <span class="font-normal text-whitetext-base">Orang</span></p>
                                <p class="text-base text-white">Pengajuan Dinas</p>
                            </div>
                        </div>
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