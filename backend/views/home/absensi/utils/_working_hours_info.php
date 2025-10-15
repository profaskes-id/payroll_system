<?php if ($jamKerjaToday): ?>

    <style>
        .moving-text {
            display: flex;
            animation: move 10s linear infinite;
        }

        @keyframes move {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>
    <div class="flex flex-col items-center justify-center space-y-3 lg:flex-row lg:space-y-0">
        <div class="w-full ">
            <p class="mt-2 -mb-3 text-xs text-center capitalize ">Lokasi Anda</p>
            <div class="bg-sky-500/10 rounded-full p-1 overflow-hidden max-w-[80dvw]  mt-3 mx-auto">
                <a href="/panel/home/your-location">
                    <div class="">
                        <div class="moving-text capitalize flex justify-around items-center text-[12px] ">
                            <p id="alamat" style="text-wrap: nowrap !important;"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="w-full ">
            <p class="mt-2 -mb-3 text-xs text-center">Jam Kerja </p>
            <div class="capitalize flex justify-around items-center bg-orange-500/10 p-1 text-[13px] w-[80%] mx-auto mt-3 rounded-full">

                <p><?= date('H:i ', strtotime($jamKerjaToday['jam_masuk'] ?? '00:00:00')) ?></p>
                <p>S/D</p>
                <p><?= date('H:i ', strtotime($jamKerjaToday['jam_keluar'] ?? '00:00:00')) ?></p>
            </div>
        </div>
    </div>
<?php else: ?>

    <?php if ($isShift): ?>
        <p class="mt-10 text-center text-gray-500">Jam Kerja Anda Dalam Bentuk Shift, Admin Belum Menentukan Jam Kerja & shift anda hari ini, <br><a href="/panel/home/pengajuan-shift?id_karyawan=<?= Yii::$app->user->identity->id_karyawan ?>" class="text-blue-500">Klik Disini Untuk Megajukan shift</a></p>
    <?php else: ?>
        <p class="mt-10 text-center text-gray-500">Sekarang bukan Hari Kerja, Anda Bisa Mengajukan Pengajuan Lembur Di Luar Hari Kerja, <br><a href="/panel/pengajuan/lembur" class="text-blue-500">Klik Disini Untuk Megajukan Lembur</a></p>
    <?php endif; ?>


<?php endif; ?>