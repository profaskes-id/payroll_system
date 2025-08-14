<?php

use yii\helpers\Html; ?>

<section class="w-full mx-auto sm:px-6 lg:px-8 min-h-[90dvh] px-4 relative z-50">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/pengajuan/tugas-luar', 'title' => 'Pengajuan Tugas Luar']); ?>

    <div class="p-4 mt-4 bg-white rounded-lg shadow-lg sm:p-6 sm:rounded-xl">
        <!-- Header -->
        <div class="pb-4 mb-4 border-b border-gray-200 sm:pb-6 sm:mb-6">
            <div class="flex flex-col">
                <div>
                    <div class="flex items-start justify-between">
                        <h2 class="text-xl font-bold text-gray-800 sm:text-2xl">Detail Tugas Luar</h2>
                        <?php if ($model->status_pengajuan == 0): ?>
                        <a href="<?= \yii\helpers\Url::to(['/pengajuan/tugas-luar-update', 'id' => $model->id_tugas_luar]) ?>" class="text-sm text-gray-600 hover:underline">Edit</a>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col mt-2 space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4 sm:mt-3">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full w-fit
                <?= $model->status_pengajuan == 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' ?>">
                            <?= $model->status_pengajuan == 0 ? 'Menunggu Persetujuan' : 'Disetujui' ?>
                        </span>
                        <span class="text-xs text-gray-500 sm:text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-3 h-3 mr-1 -mt-0.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 mt-4">
                    <!-- Jumlah Tugas -->
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 rounded-full bg-blue-50 sm:px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?= count($model->detailTugasLuars) ?> Tugas
                    </span>


                    <div class="relative group">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 rounded-full bg-gray-50 sm:px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <div class="text-gray-700"><?= Html::encode($model->catatan_approver) ?></div>
                        </span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Task List -->
        <div class="space-y-2 sm:space-y-3">
            <?php $counter = 0 ?>
            <?php foreach ($model->detailTugasLuars as $index => $detail): ?>
                <?php if ($detail->status_pengajuan_detail == 1): ?>
                    <div class="flex items-center justify-between p-3 transition-all bg-white border border-gray-200 rounded-lg sm:p-5 hover:border-blue-300 hover:shadow-sm group">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start">
                                <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 mt-0.5 mr-2 text-sm font-bold text-blue-600 rounded-full sm:w-8 sm:h-8 sm:text-lg sm:mt-1 sm:mr-3 bg-blue-50">
                                    <?= ++$counter ?>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-800 truncate sm:text-lg"><?= $detail->keterangan ?></h3>
                                    <div class="flex flex-wrap items-center mt-0.5 space-x-2 text-xs sm:space-x-4 sm:mt-1 sm:text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-0.5 sm:w-4 sm:h-4 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <?= date('H:i', strtotime($detail->jam_diajukan)) ?>
                                        </span>
                                        <?php if ($detail->status_check == 1): ?>
                                            <?php
                                            $jamDiajukan = new DateTime($detail->jam_diajukan);
                                            $jamCheckIn = new DateTime($detail->jam_check_in);
                                            $selisih = $jamDiajukan->diff($jamCheckIn);

                                            $menitSelisih = ($selisih->h * 60) + $selisih->i;
                                            $isLate = $jamCheckIn > $jamDiajukan;
                                            $selisihText = '';

                                            if ($selisih->h > 0) {
                                                $selisihText .= $selisih->h . ' jam ';
                                            }
                                            if ($selisih->i > 0) {
                                                $selisihText .= $selisih->i . ' menit';
                                            }
                                            $selisihText = trim($selisihText);
                                            ?>

                                            <span class="flex items-center <?= $isLate ? 'text-red-600' : 'text-green-600' ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-0.5 sm:w-4 sm:h-4 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <?= date('H:i', strtotime($detail->jam_check_in)) ?>
                                                <span class="ml-1 text-xs <?= $isLate ? 'text-red-500' : 'text-green-500' ?>">
                                                    (<?= $isLate ? '+' : '-' ?><?= $selisihText ?>)
                                                </span>
                                                <span class="inline-block ">
                                                    <?php
                                                    $imagePath = Yii::getAlias("@webroot") . '/uploads/bukti_tugas_luar/' . $detail->bukti_foto;
                                                    $defaultImage = Yii::getAlias("@webroot") . '/images/default-image.jpg'; // Siapkan gambar default
                                                    ?>

                                                    <?php if (!empty($detail->bukti_foto) && file_exists($imagePath)): ?>
                                                        <img src="<?= Yii::getAlias("@web") ?>/uploads/bukti_tugas_luar/<?= $detail->bukti_foto ?>"
                                                            alt="Bukti Tugas Luar"
                                                            class="object-cover border border-gray-200 rounded-lg w-14 sm:w-24 sm:h-24 ms-5">
                                                    <?php else: ?>
                                                        <div class="flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg w-14 h-14 ms-5 sm:w-24 sm:h-24">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ml-2 sm:ml-4">
                            <?php if ($detail->status_check == 1): ?>
                                <div class="flex items-center justify-center w-8 h-8 rounded-full shadow-sm sm:w-12 sm:h-12 bg-gradient-to-br from-green-400 to-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white sm:w-6 sm:h-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            <?php else: ?>
                                <button class="flex items-center justify-center w-8 h-8 transition-all rounded-full shadow-sm sm:w-12 sm:h-12 check-in-btn bg-gradient-to-br from-amber-400 to-amber-600 hover:from-amber-500 hover:to-amber-700 hover:shadow-md"
                                    data-id="<?= $detail->id_detail ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white sm:w-6 sm:h-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Check-in Modal (Mobile Optimized) -->
    <div id="checkInModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
        <div class="w-full mx-2 overflow-hidden bg-white shadow-xl rounded-xl sm:max-w-md sm:mx-4 sm:rounded-2xl">
            <!-- Header tetap sama -->
            <div class="p-4 sm:p-6 bg-gradient-to-r from-blue-600 to-blue-500">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white sm:text-xl">Check-in Tugas</h3>
                    <button id="closeModal" class="text-blue-100 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-4 space-y-4 sm:p-6 sm:space-y-6">
                <!-- Lokasi tetap sama -->
                <div class="p-3 rounded-lg sm:p-4 bg-gray-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-1 text-blue-600 bg-blue-100 rounded-full sm:p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p id="locationStatus" class="text-xs font-medium text-gray-700 sm:text-sm">Mendeteksi lokasi...</p>
                            <div id="coordinates" class="mt-1 space-y-1 text-xs sm:mt-2 sm:text-sm">
                                <p class="font-mono text-gray-800" id="alamat"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input file dengan preview gambar -->
                <div class="space-y-2">
                    <input type="file"
                        id="buktiFoto"
                        name="bukti_foto"
                        accept="image/*"
                        class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 sm:text-sm">

                    <!-- Preview gambar -->
                    <div id="imagePreviewContainer" class="hidden mt-2">
                        <p class="text-xs font-medium text-gray-500">Preview:</p>
                        <img id="imagePreview" src="#" alt="Preview gambar" class="object-cover w-20 h-20 mt-1 border border-gray-200 rounded-lg sm:w-24 sm:h-24">
                    </div>
                </div>

                <input type="hidden" id="detailId">
            </div>

            <!-- Footer tetap sama -->
            <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gray-50">
                <div class="flex justify-end space-x-2 sm:space-x-3">
                    <button id="cancelCheckIn" class="px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg sm:px-5 sm:py-2 sm:text-sm hover:bg-gray-50">
                        Batal
                    </button>
                    <button id="submitCheckIn" class="px-3 py-1.5 text-xs font-medium text-white transition-colors bg-blue-600 rounded-lg sm:px-5 sm:py-2 sm:text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Check-in
                    </button>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('buktiFoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImage = document.getElementById('imagePreview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
            previewImage.src = '#';
        }
    });
</script>


<script>
    $(document).ready(function() {

        function dapatkanAlamat(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    var alamatLengkap = data.display_name;
                    var elemenAlamat = document.getElementById('alamat');
                    if (elemenAlamat) {
                        elemenAlamat.textContent = alamatLengkap;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }





        let currentDetailId = null;
        let currentPosition = null;

        // Function to format current time
        function getCurrentTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            return `${hours}:${minutes}:${seconds}`;
        }

        // Update time every second
        function updateCurrentTime() {
            $('#currentTimeDisplay').text(`Waktu: ${getCurrentTime()}`);
        }

        // Open modal when check-in button clicked
        $(document).on('click', '.check-in-btn', function() {
            currentDetailId = $(this).data('id');
            $('#detailId').val(currentDetailId);
            $('#checkInModal').removeClass('hidden');
            getCurrentLocation();
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000); // Update time every second
        });

        // Close modal
        $('#closeModal, #cancelCheckIn').click(function() {
            $('#checkInModal').addClass('hidden');
            clearInterval(updateCurrentTime);
        });

        // Submit check-in
        $('#submitCheckIn').click(function() {
            if (!currentPosition) {
                alert('Lokasi belum terdeteksi. Silakan coba lagi.');
                return;
            }

            const fileInput = $('#buktiFoto')[0];
            if (fileInput.files.length === 0) {
                alert('Silakan upload bukti foto terlebih dahulu');
                return;
            }

            const formData = new FormData();
            formData.append('latitude', currentPosition.coords.latitude);
            formData.append('longitude', currentPosition.coords.longitude);
            formData.append('waktu', new Date().toLocaleString('sv-SE'));
            formData.append('bukti_foto', fileInput.files[0]);

            $.ajax({
                type: "POST",
                url: "/panel/pengajuan/checkin-tugas-luar?id=" + currentDetailId,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Check-in berhasil!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });

            $('#checkInModal').addClass('hidden');
        });

        // Get current location
        function getCurrentLocation() {
            $('#locationStatus').text('Mendeteksi lokasi...');
            $('#latitudeDisplay').text('');
            $('#longitudeDisplay').text('');
            // $('#currentTimeDisplay').text('');
            $('#alamat').text('loading...');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentPosition = position;
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        $('#locationStatus').text('Lokasi berhasil dideteksi:');
                        $('#latitudeDisplay').text(`Latitude: ${lat.toFixed(6)}`);
                        $('#longitudeDisplay').text(`Longitude: ${lng.toFixed(6)}`);
                        dapatkanAlamat(lat, lng);
                    },
                    function(error) {
                        let errorMessage = 'Gagal mendapatkan lokasi: ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Izin ditolak oleh pengguna.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Informasi lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'Permintaan lokasi timeout.';
                                break;
                            case error.UNKNOWN_ERROR:
                                errorMessage += 'Error tidak diketahui.';
                                break;
                        }
                        $('#locationStatus').text(errorMessage);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                $('#locationStatus').text('Browser tidak mendukung geolokasi');
            }
        }
    });
</script>