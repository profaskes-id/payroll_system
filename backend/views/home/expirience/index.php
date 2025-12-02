<?php

use backend\models\helpers\FaceRecognationHelper;
use backend\models\MasterKode;
use yii\helpers\Html;

$this->title = 'Expirience';

?>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    #modal-toggle:checked~.modal-overlay {
        display: flex;
    }

    .modal-content {
        position: relative;
        background: white;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
    }

    .modal-close {
        position: absolute;
        top: -15px;
        right: -15px;
        background: #333;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
    }
</style>

<div class="container relative mx-auto px-3 lg:px-5 min-h-[90dvh] z-50">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'Data & Riwayat']); ?>
    <section>
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-karyawan" type="button" role="tab" aria-controls="karyawan" aria-selected="false">Personal
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">RIW Pekerjaan
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">RIW Pendidikan

                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Keluarga

                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-pelatihan" type="button" role="tab" aria-controls="pelatihan" aria-selected="false">RIW Pelatihan

                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-styled-tab" data-tabs-target="#styled-kesehatan" type="button" role="tab" aria-controls="kesehatan" aria-selected="false">Kesehatan

                    </button>
                </li>

            </ul>
        </div>
        <div id="default-styled-tab-content">
            <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-karyawan" role="tabpanel" aria-labelledby="karyawan-tab">
                <h2 class="font-semibold text-gray-900 ">Data Personal </h2>
                <?php if (!empty($karyawan)) : ?>
                    <div class="grid grid-cols-1 mt-2 gap-y-3">

                        <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                            <div class="grid grid-cols-12 gap-2">
                                <div class="w-full col-span-12 p-2 border border-gray-300 lg:col-span-6">
                                    <p>Data Pribadi</p>
                                    <div>
                                        <table class="w-full text-left table-fixed ">
                                            <tr class="border-b border-gray-300 ">
                                                <th>Kode Karyawan</th>
                                                <td><?= $karyawan->kode_karyawan ?></td>
                                            </tr>

                                            <tr class="border-b border-gray-300 ">
                                                <th>Nama Karyawan</th>
                                                <td><?= $karyawan->nama ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Email</th>
                                                <td><?= $karyawan->email ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Nomer Telfon</th>
                                                <td><?= $karyawan->nomer_telepon ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Nomer Identitas</th>
                                                <td><?= $karyawan->nomer_identitas ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Jenis Identitas</th>
                                                <td><?= $karyawan->jenisidentitas->nama_kode ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Jenis Kelamin</th>
                                                <td><?= $karyawan->kode_jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Tempat Lahir</th>
                                                <td><?= $karyawan->tempat_lahir ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Tanggal Lahir</th>
                                                <td><?= date('d M Y', strtotime($karyawan->tanggal_lahir)) ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Status Nikah</th>
                                                <td><?= $karyawan->statusNikah->nama_kode ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300">
                                                <th>Suku</th>
                                                <td><?= $karyawan->suku ?></td>
                                            </tr>
                                            <tr class="">
                                                <th>Agama</th>
                                                <td><?= $karyawan->masterAgama->nama_kode ?></td>
                                            </tr>




                                            <tr class="">
                                                <th>wajah</th>
                                                <td>
                                                    <!-- Trigger -->
                                                    <label for="modal-toggle" style="cursor:pointer;">
                                                        <?= Html::img(
                                                            'data:image/jpeg;base64,' . $karyawan->wajah,
                                                            [
                                                                'style' => 'width:120px;  object-fit:contain; border-radius:8px;',
                                                                'onclick' => 'document.getElementById("modal-image").src="data:image/jpeg;base64,' . $karyawan->wajah . '"'
                                                            ]
                                                        ) ?>
                                                    </label>

                                                    <!-- Modal -->
                                                    <input type="checkbox" id="modal-toggle" style="display:none;">
                                                    <div class="modal-overlay">
                                                        <label for="modal-toggle" class="modal-background"></label>
                                                        <div class="modal-content">
                                                            <img id="modal-image" src="" style="max-height:90vh; max-width:90vw;">
                                                            <label for="modal-toggle" class="modal-close">&times;</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>


                                    <?php
                                    $setting_fr = FaceRecognationHelper::cekFaceRecognation();
                                    if ($setting_fr == 1):
                                    ?>

                                        <div class="w-full col-span-12 p-2 border border-gray-300 lg:col-span-6">
                                            <div class="mt-4">
                                                <button type="button" onclick="openFaceModal()" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                                    <?= $karyawan->wajah ? "Update Wajah" :  "Register Wajah" ?>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Modal for face registration -->
                                    <div id="faceModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                            </div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                                                    <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">Register Wajah</h3>
                                                    <div class="mb-4">
                                                        <div class="relative">
                                                            <video id="video" class="w-full h-64 mb-4 bg-gray-200 rounded" autoplay playsinline muted></video>
                                                            <canvas id="overlay" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
                                                            <div id="liveness-instruction" class="absolute left-0 right-0 text-center top-2">
                                                                <div class="inline-block px-3 py-1 text-sm text-white bg-black rounded-full bg-opacity-70">
                                                                    Tekan "Mulai Verifikasi" untuk memulai
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="controls" class="flex justify-center space-x-4">
                                                            <button id="startLivenessBtn" onclick="startLiveness()" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Mulai Verifikasi</button>
                                                            <button onclick="stopCamera()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Tutup</button>
                                                        </div>
                                                    </div>
                                                    <div id="results" class="hidden">
                                                        <p class="mb-2 font-medium">Hasil Foto:</p>
                                                        <div id="snapshotResult" class="mx-auto mb-4 overflow-hidden bg-gray-200 rounded"></div>
                                                        <?php $form = yii\widgets\ActiveForm::begin([
                                                            'id' => 'face-form',
                                                            'action' => ['karyawan/upload-face'],
                                                            'options' => ['enctype' => 'multipart/form-data']
                                                        ]); ?>

                                                        <?= $form->field($model, 'kode_karyawan')->textInput(['value' => $karyawan->kode_karyawan])->label(false) ?>
                                                        <?= $form->field($model, 'wajah')->textInput(['id' => 'faceData'])->label(false) ?>
                                                        <?= $form->field($model, 'liveness_passed')->textInput(['id' => 'livenessPassed', 'value' => '0'])->label(false) ?>

                                                        <div class="flex justify-end space-x-2" id="faceControls" style="display:none;">
                                                            <button type="button" onclick="resetCameraView(); startCamera();" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Ambil Ulang</button>
                                                            <button type="submit" class="relative px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700" id="submitButton" onclick="handleSubmit()">
                                                                <span id="buttonText">Simpan</span>
                                                                <span id="loadingSpinner" class="hidden inline-block ml-2">
                                                                    <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                            <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                                                        </div>
                                                        <?php yii\widgets\ActiveForm::end(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="w-full col-span-12 p-2 border border-gray-300 lg:col-span-6">
                                    <p>Alamat Sesuai Identitas</p>
                                    <div>
                                        <table class="w-full text-left table-fixed ">
                                            <tr class="border-b border-gray-300 ">
                                                <th>Negara</th>
                                                <td><?= $karyawan->kode_negara ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Provinsi</th>
                                                <td><?= $karyawan->provinsiidentitas->nama_prop ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Kabupaten</th>
                                                <td><?= $karyawan->kabupatenidentitas->nama_kab ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Kecamatan</th>
                                                <td><?= $karyawan->kecamatanidentitas->nama_kec ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Alamat Identitas</th>
                                                <td><?= $karyawan->alamat_identitas ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Desa Atau Lurah</th>
                                                <td><?= $karyawan->desa_lurah_identitas ?></td>
                                            </tr>

                                            <tr class="border-b border-gray-300 ">
                                                <th>RT</th>
                                                <td><?= $karyawan->rt_identitas ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300">
                                                <th>RW</th>
                                                <td><?= $karyawan->rw_identitas ?></td>
                                            </tr>
                                            <tr class="">
                                                <th>Informasi Lain</th>
                                                <td><?= $karyawan->informasi_lain ?></td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                                <div class="w-full col-span-12 p-2 border border-gray-300 justify-self-end">
                                    <p>Alamat Domisili</p>
                                    <div>
                                        <table class="w-full text-left table-fixed ">
                                            <tr class="border-b border-gray-300 ">
                                                <th>Negara</th>
                                                <td><?= $karyawan->kode_negara ?></td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Provinsi</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->provinsidomisili->nama_prop;
                                                    } else {
                                                        echo $karyawan->provinsiidentitas->nama_prop;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Kabupaten</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->kabupatendomisili->nama_kab;
                                                    } else {
                                                        echo $karyawan->kabupatenidentitas->nama_kab;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Kecamatan</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->kecamatandomisili->nama_kec;
                                                    } else {
                                                        echo $karyawan->kecamatanidentitas->nama_kec;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Alamat Identitas</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->alamat_domisili;
                                                    } else {
                                                        echo $karyawan->alamat_identitas;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Desa Atau Lurah</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->desa_lurah_domisili;
                                                    } else {
                                                        echo $karyawan->desa_lurah_identitas;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr class="border-b border-gray-300 ">
                                                <th>RT</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->rt_domisili;
                                                    } else {
                                                        echo $karyawan->rt_identitas;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="border-gray-300 ">
                                                <th>RW</th>
                                                <td><?php
                                                    if ($karyawan->is_current_domisili == 0) {
                                                        echo $karyawan->rw_domisili;
                                                    } else {
                                                        echo $karyawan->rw_identitas;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>


                                        </table>
                                    </div>
                                </div>
                                <div class="w-full col-span-12 p-2 border border-gray-300 justify-self-end">
                                    <p>Alamat Domisili</p>
                                    <div>
                                        <table class="w-full text-left table-fixed ">
                                            <tr class="border-b border-gray-300 ">
                                                <th>Foto</th>
                                                <td>
                                                    <div>
                                                        <?php if (!$karyawan->foto == null): ?>
                                                            <?= Html::a('preview', [$karyawan->foto], ['class' => 'text-blue-500', 'target' => '_blank']) ?>
                                                        <?php else: ?>
                                                            <p>Belum Ada</p>
                                                        <?php endif ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>KTP</th>
                                                <td>
                                                    <div>
                                                        <?php if (!$karyawan->ktp == null): ?>
                                                            <?= Html::a('preview', [$karyawan->ktp], ['class' => 'text-blue-500', 'target' => '_blank']) ?>
                                                        <?php else: ?>
                                                            <p>Belum Ada</p>
                                                        <?php endif ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>CV</th>
                                                <td>
                                                    <div>
                                                        <?php if (!$karyawan->cv == null): ?>
                                                            <?= Html::a('preview', [$karyawan->cv], ['class' => 'text-blue-500', 'target' => '_blank']) ?>
                                                        <?php else: ?>
                                                            <p>Belum Ada</p>
                                                        <?php endif ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="border-b border-gray-300 ">
                                                <th>Ijazah Terakhir</th>
                                                <td>
                                                    <div>
                                                        <?php if (!$karyawan->ijazah_terakhir == null): ?>
                                                            <?= Html::a('preview', [$karyawan->ijazah_terakhir], ['class' => 'text-blue-500', 'target' => '_blank']) ?>
                                                        <?php else: ?>
                                                            <p>Belum Ada</p>
                                                        <?php endif ?>
                                                    </div>
                                                </td>
                                            </tr>


                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                        <div>
                            <p class="py-2 text-[#272727]">Belum Ada Data</p>
                        </div>

                    </div>
                <?php endif ?>
            </div>
        </div>
        <div class="hidden p-4 bg-gray-100 rounded-lg dark:bg-gray-800" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">

            <h2 class="font-semibold text-gray-900 ">Pengalaman Kerja <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($pengalamanKerja) ?></span>
            </h2>
            <a href="/panel/home/expirience-pekerjaan-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
            <div class="grid grid-cols-1 mt-2 gap-y-3">

                <?php if (!empty($pengalamanKerja)) : ?>
                    <?php foreach ($pengalamanKerja as $key => $value) : ?>

                        <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                            <div>
                                <p class="font-semibold text-[#272727]"><?= $value['posisi'] ?></p>
                                <p class="text-[13px] text-black/80"><?= $value['perusahaan'] ?></p>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <p class="text-[13px] text-black/80"><?= date('d-m-Y', strtotime($value['masuk_pada'])) ?> &nbsp;&nbsp;~&nbsp;&nbsp; <?= date('d-m-Y', strtotime($value['keluar_pada'])) ?></p>
                                <div class="flex space-x-2">
                                    <a href="/panel/home/expirience-pekerjaan-update?id=<?= $value['id_pengalaman_kerja'] ?>" class="flex items-center justify-center">

                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                            </svg></p>
                                        <p>
                                    </a>


                                    <form action="/panel/home/expirience-pekerjaan-delete" method="POST">
                                        <input type="text" name="id" value="<?= $value['id_pengalaman_kerja'] ?>" hidden>
                                        <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="grid place-items-center" onclick="deleteClick(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else : ?>
                    <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                        <div>
                            <p class="py-2 text-[#272727]">Belum Ada Data</p>
                        </div>
                    </div>
                <?php endif ?>


            </div>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
            <h2 class="font-semibold text-gray-900 ">Riwayat Pendidikan <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($riwayatPendidikan) ?></span> </h2>
            <a href="/panel/home/expirience-pendidikan-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
            <div class="grid grid-cols-1 mt-2 gap-y-3">

                <?php if (!empty($riwayatPendidikan)) : ?>
                    <?php foreach ($riwayatPendidikan as $key => $value) : ?>
                        <?php $jenajngPendidikan = MasterKode::find()->select('nama_kode')->where(['nama_group' => 'jenjang-pendidikan', 'kode' => $value['jenjang_pendidikan']])->one(); ?>
                        <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                            <div>
                                <p class="font-semibold text-[#272727]"><?= $jenajngPendidikan['nama_kode'] ?></p>
                                <p class="text-[13px] text-black/80"><?= $value['institusi'] ?></p>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <p class="text-[13px] text-black/80"><?= $value['tahun_masuk']; ?> &nbsp;&nbsp;~&nbsp;&nbsp; <?= $value['tahun_keluar']; ?></p>
                                <div class="flex space-x-2">
                                    <a href="/panel/home/expirience-pendidikan-update?id=<?= $value['id_riwayat_pendidikan'] ?>" class="flex items-center justify-center">

                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                            </svg></p>
                                        <p>
                                    </a>


                                    <form action="/panel/home/expirience-pendidikan-delete" method="POST">
                                        <input type="text" name="id" value="<?= $value['id_riwayat_pendidikan'] ?>" hidden>
                                        <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="grid place-items-center" onclick="deleteClick(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>


                <?php else : ?>
                    <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                        <div>
                            <p class="py-2 text-[#272727]">Belum Ada Data</p>
                        </div>

                    </div>
                <?php endif ?>

            </div>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-settings" role="tabpanel" aria-labelledby="settings-tab">

            <h2 class="font-semibold text-gray-900 ">Data Keluarga <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($keluarga) ?></span> </h2>
            <a href="/panel/home/data-keluarga-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
            <div class="grid grid-cols-1 mt-2 gap-y-3">

                <?php if (!empty($keluarga)) : ?>
                    <?php foreach ($keluarga as $key => $value) : ?>
                        <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                            <div>
                                <p class="font-semibold text-[#272727]">Nama : <?= $value['nama_anggota_keluarga'] ?></p>
                                <p class="text-[13px] text-black/80">Hubungan : <?= $value->jenisHubungan->nama_kode ?></p>
                                <p class="text-[13px] text-black/80">lahir pada : <?= $value['tahun_lahir'] ?> </p>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <p class="text-[13px] text-black/80">Pekerjaan : <?= $value['pekerjaan'] ?></p>
                                <div class="flex space-x-2">
                                    <a href="/panel/home/data-keluarga-update?id=<?= $value['id_data_keluarga'] ?>" class="flex items-center justify-center">

                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                            </svg></p>
                                        <p>
                                    </a>


                                    <form action="/panel/home/data-keluarga-delete" method="POST">
                                        <input type="text" name="id" value="<?= $value['id_data_keluarga'] ?>" hidden>
                                        <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="grid place-items-center" onclick="deleteClick(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                <?php else: ?>
                    <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                        <div>
                            <p class="py-2 text-[#272727]">Belum Ada Data</p>
                        </div>

                    </div>
                <?php endif ?>



            </div>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-pelatihan" role="tabpanel" aria-labelledby="pelatihan-tab">

            <h2 class="font-semibold text-gray-900 ">Riwayat Pelatihan <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($RiwayatPelatihan) ?></span> </h2>
            <a href="/panel/home/riwayat-pelatihan-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
            <div class="grid grid-cols-1 mt-2 gap-y-3">

                <?php if (!empty($RiwayatPelatihan)) : ?>
                    <?php foreach ($RiwayatPelatihan as $key => $value) : ?>
                        <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                            <div>
                                <p class="font-semibold text-[#272727]"><?= $value['judul_pelatihan'] ?></p>
                                <p class="text-[13px] text-black/80"><?= $value['penyelenggara'] ?></p>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <p class="text-[13px] text-black/80"><?= date('d-m-Y', strtotime($value['tanggal_mulai'])) ?> &nbsp;&nbsp;~&nbsp;&nbsp; <?= date('d-m-Y', strtotime($value['tanggal_selesai'])) ?></p>
                                <div class="flex space-x-2">
                                    <a href="/panel/home/riwayat-pelatihan-update?id=<?= $value['id_riwayat_pelatihan'] ?>" class="flex items-center justify-center">

                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                            </svg></p>
                                        <p>
                                    </a>


                                    <form action="/panel/home/riwayat-pelatihan-delete" method="POST">
                                        <input type="text" name="id" value="<?= $value['id_riwayat_pelatihan'] ?>" hidden>
                                        <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="grid place-items-center" onclick="deleteClick(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                <?php else: ?>
                    <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                        <div>
                            <p class="py-2 text-[#272727]">Belum Ada Data</p>
                        </div>

                    </div>
                <?php endif ?>



            </div>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="styled-kesehatan" role="tabpanel" aria-labelledby="kesehatan-tab">

            <h2 class="font-semibold text-gray-900 ">Riwayat Kesehatan <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"><?= count($RiwayatKesehatan) ?></span> </h2>
            <a href="/panel/home/riwayat-kesehatan-create" class="flex items-center justify-center w-full px-4 py-1 my-3 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-lg gap-x-2 hover:bg-blue-400 focus:outline-none focus:bg-blue-400">+ Add New</a>
            <div class="grid grid-cols-1 mt-2 gap-y-3">

                <?php if (!empty($RiwayatKesehatan)) : ?>
                    <?php foreach ($RiwayatKesehatan as $key => $value) : ?>
                        <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                            <div>
                                <p class="font-semibold text-[#272727]"><?= $value['nama_pengecekan'] ?></p>
                                <p class="text-[13px] text-black/80"><?= $value['keterangan'] ?></p>
                            </div>
                            <hr>
                            <div class="flex justify-between">
                                <p class="text-[13px] text-black/80"><?= date('d-m-Y', strtotime($value['tanggal'])) ?> </p>
                                <div class="flex space-x-2">
                                    <a href="/panel/home/riwayat-kesehatan-update?id=<?= $value['id_riwayat_kesehatan'] ?>" class="flex items-center justify-center">

                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="black" fill-rule="evenodd" d="M21.455 5.416a.75.75 0 0 1-.096.943l-9.193 9.192a.75.75 0 0 1-.34.195l-3.829 1a.75.75 0 0 1-.915-.915l1-3.828a.8.8 0 0 1 .161-.312L17.47 2.47a.75.75 0 0 1 1.06 0l2.829 2.828a1 1 0 0 1 .096.118m-1.687.412L18 4.061l-8.518 8.518l-.625 2.393l2.393-.625z" clip-rule="evenodd" />
                                                <path fill="black" d="M19.641 17.16a44.4 44.4 0 0 0 .261-7.04a.4.4 0 0 1 .117-.3l.984-.984a.198.198 0 0 1 .338.127a46 46 0 0 1-.21 8.372c-.236 2.022-1.86 3.607-3.873 3.832a47.8 47.8 0 0 1-10.516 0c-2.012-.225-3.637-1.81-3.873-3.832a46 46 0 0 1 0-10.67c.236-2.022 1.86-3.607 3.873-3.832a48 48 0 0 1 7.989-.213a.2.2 0 0 1 .128.34l-.993.992a.4.4 0 0 1-.297.117a46 46 0 0 0-6.66.255a2.89 2.89 0 0 0-2.55 2.516a44.4 44.4 0 0 0 0 10.32a2.89 2.89 0 0 0 2.55 2.516c3.355.375 6.827.375 10.183 0a2.89 2.89 0 0 0 2.55-2.516" />
                                            </svg></p>
                                        <p>
                                    </a>


                                    <form action="/panel/home/riwayat-kesehatan-delete" method="POST">
                                        <input type="text" name="id" value="<?= $value['id_riwayat_kesehatan'] ?>" hidden>
                                        <button type="button" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="grid place-items-center" onclick="deleteClick(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="red" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                <?php else: ?>
                    <div class="flex flex-col w-full p-2 space-y-1 bg-white rounded ">
                        <div>
                            <p class="py-2 text-[#272727]">Belum Ada Data</p>
                        </div>

                    </div>
                <?php endif ?>



            </div>
        </div>



        <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full p-4">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 text-center md:p-5">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Yakin Melakukan Hapus Data?</h3>
                        <button id="delete-button" data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, Hapus
                        </button>
                        <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<!-- script 1 -->
<script>
    const MODEL_URL = '<?= Yii::getAlias('@root'); ?>/panel/models';
    let video, canvas, ctx, detectionInterval;
    let livenessPassed = false;
    let isModelsLoaded = false;
    let faceRecognitionNetLoaded = false;

    // Load hanya tiny + landmark
    async function loadModels() {
        if (isModelsLoaded) return;
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            isModelsLoaded = true;
            console.log('Models loaded: tinyFaceDetector + faceLandmark68Net');
        } catch (err) {
            alert('Gagal load model: ' + err.message);
            console.error(err);
        }
    }

    async function loadFaceRecognitionNet() {
        if (faceRecognitionNetLoaded) return;
        try {
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            faceRecognitionNetLoaded = true;
            console.log('faceRecognitionNet loaded');
        } catch (err) {
            alert('Gagal load faceRecognitionNet: ' + err.message);
            console.error(err);
        }
    }

    function openFaceModal() {
        document.getElementById('faceModal').classList.remove('hidden');
        resetCameraView();
        loadModels().then(() => {
            startCamera();
            startFaceOverlay();
        });
    }

    function closeModal() {
        document.getElementById('faceModal').classList.add('hidden');
        stopCamera();
        stopFaceOverlay();
        document.getElementById('results').classList.add('hidden');
        document.getElementById('faceControls').style.display = 'none';
    }

    function startCamera() {
        video = document.getElementById('video');
        canvas = document.getElementById('overlay');
        ctx = canvas.getContext('2d');

        // TAMBAHKAN CSS UNTUK NON-MIRROR (ASLI WEBCAM) DAN KOTAK
        video.style.transform = 'scaleX(1)'; // Non-mirror
        video.style.width = '300px';
        video.style.height = '300px';
        video.style.objectFit = 'cover';
        video.style.borderRadius = '8px';
        video.style.overflow = 'hidden';

        canvas.style.width = '300px';
        canvas.style.height = '300px';
        canvas.style.objectFit = 'cover';
        canvas.style.borderRadius = '8px';
        canvas.style.position = 'absolute';
        canvas.style.top = '0';
        canvas.style.left = '0';

        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: {
                        ideal: 300,
                        max: 300
                    }, // Batasi ukuran
                    height: {
                        ideal: 300,
                        max: 300
                    }, // Batasi ukuran
                    aspectRatio: 1 // Force 1:1 aspect ratio
                }
            })
            .then(stream => {
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    video.play();

                    // Set canvas size sesuai dengan video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // PASTIKAN TETAP NON-MIRROR DAN KOTAK
                    video.style.transform = 'scaleX(1)';
                    video.style.width = '300px';
                    video.style.height = '300px';
                    video.style.objectFit = 'cover';
                };
            })
            .catch(err => alert('Kamera error: ' + err.message));
    }

    function stopCamera() {
        if (video?.srcObject) video.srcObject.getTracks().forEach(t => t.stop());
    }

    function startFaceOverlay() {
        if (!isModelsLoaded || !video) return;
        stopFaceOverlay();

        detectionInterval = setInterval(async () => {
            if (!video || video.paused || video.ended) return;

            try {
                const detections = await faceapi
                    .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({
                        inputSize: 320,
                        scoreThreshold: 0.4
                    }));

                const resized = faceapi.resizeResults(detections, {
                    width: 300,
                    height: 300
                });
                overlayCtx.clearRect(0, 0, 300, 300);
                faceapi.draw.drawDetections(canvas, resized);
            } catch (err) {}
        }, 100);
    }

    function stopFaceOverlay() {
        if (detectionInterval) clearInterval(detectionInterval);
        detectionInterval = null;
        if (overlayCtx) overlayCtx.clearRect(0, 0, 300, 300); // Ubah di sini juga
    }

    function resetCameraView() {
        document.getElementById('results').classList.add('hidden');
        document.getElementById('faceControls').style.display = 'none';
        document.getElementById('snapshotResult').innerHTML = '';
        document.getElementById('faceData').value = '';
        document.getElementById('livenessPassed').value = '0';
        livenessPassed = false;
        document.getElementById('liveness-instruction').innerHTML =
            '<div class="inline-block px-3 py-1 text-sm text-white bg-black rounded-full bg-opacity-70">Tekan "Mulai Verifikasi"</div>';
        document.getElementById('startLivenessBtn').disabled = false;
    }

    async function detectHeadPose() {
        if (!isModelsLoaded) return null;
        try {
            // PAKSA TINY DETECTOR DI SINI JUGA
            const detection = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                    inputSize: 320,
                    scoreThreshold: 0.4
                }))
                .withFaceLandmarks();
            if (!detection) return null;

            const l = detection.landmarks;
            const leftEye = l.getLeftEye()[0];
            const rightEye = l.getRightEye()[3];
            const nose = l.getNose()[3];
            const eyeDist = rightEye.x - leftEye.x;
            const yaw = ((nose.x - leftEye.x) - (rightEye.x - nose.x)) / eyeDist * 100;
            return yaw;
        } catch (err) {
            return null;
        }
    }

    async function waitForPose(dir, timeout = 5000) {
        return new Promise(resolve => {
            const start = Date.now();
            const check = async () => {
                if (Date.now() - start > timeout) return resolve(false);
                const yaw = await detectHeadPose();
                if (yaw === null) return setTimeout(check, 300);
                if (dir === 'left' && yaw < -12) resolve(true);
                else if (dir === 'right' && yaw > 12) resolve(true);
                else if (dir === 'center' && Math.abs(yaw) < 8) resolve(true);
                else setTimeout(check, 300);
            };
            check();
        });
    }

    async function startLiveness() {
        if (!isModelsLoaded) {
            alert('Model belum siap!');
            return;
        }

        document.getElementById('startLivenessBtn').disabled = true;
        const inst = document.getElementById('liveness-instruction');

        const steps = [{
                text: 'Tatap lurus',
                dir: null,
                timeout: 2000
            },
            {
                text: 'Lihat ke kiri',
                dir: 'left',
                timeout: 5000
            },
            {
                text: 'Lihat ke kanan',
                dir: 'right',
                timeout: 5000
            },
            {
                text: 'Kembali ke tengah',
                dir: 'center',
                timeout: 4000
            }
        ];

        for (let s of steps) {
            if (!s.dir) {
                inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-blue-600 rounded-full">${s.text}</div>`;
                await new Promise(r => setTimeout(r, s.timeout));
                continue;
            }
            inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-orange-600 rounded-full">${s.text}</div>`;
            const ok = await waitForPose(s.dir, s.timeout);
            if (!ok) {
                inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-red-600 rounded-full">Gagal: ${s.text}</div>`;
                setTimeout(() => {
                    document.getElementById('startLivenessBtn').disabled = false;
                }, 2000);
                return;
            }
        }

        inst.innerHTML = `<div class="inline-block px-3 py-1 text-sm text-white bg-green-600 rounded-full">Liveness lolos!</div>`;
        livenessPassed = true;

        // HITUNG & TAMPILKAN DESCRIPTOR
        await computeAndSaveDescriptor();
    }

    // HITUNG & SIMPAN DESCRIPTOR + TAMPILKAN DI CONSOLE
    async function computeAndSaveDescriptor() {
        try {
            // Pastikan faceRecognitionNet sudah loaded
            await loadFaceRecognitionNet();

            // BUAT CANVAS 300x300 SAMA SEPERTI SCRIPT 2
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = 300;
            tempCanvas.height = 300;
            const tempCtx = tempCanvas.getContext('2d');

            // CROP VIDEO KE 300x300 (sama seperti di takeVerifiedPhoto)
            const videoWidth = video.videoWidth;
            const videoHeight = video.videoHeight;
            const size = Math.min(videoWidth, videoHeight);
            const offsetX = (videoWidth - size) / 2;
            const offsetY = (videoHeight - size) / 2;

            tempCtx.drawImage(video, offsetX, offsetY, size, size, 0, 0, 300, 300);

            // PAKSA TINY DETECTOR PADA CANVAS 300x300 (SAMA DENGAN SCRIPT 2)
            const detection = await faceapi
                .detectSingleFace(tempCanvas, new faceapi.TinyFaceDetectorOptions({
                    inputSize: 320,
                    scoreThreshold: 0.4
                }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                alert('Wajah tidak terdeteksi saat menghitung descriptor');
                return;
            }

            const descriptor = detection.descriptor;
            const descriptorArray = Array.from(descriptor);
            const descriptorString = descriptorArray.join(',');

            // TAMPILKAN DI CONSOLE
            console.log('%c[FACE DESCRIPTOR Script 1] Liveness lolos!', 'color: green; font-weight: bold;');
            console.log('Descriptor (128 float):', descriptorArray);
            console.log('String (untuk DB):', descriptorString);
            console.log('Face detection pada gambar: 300x300 (cropped)');

            // MASUKKAN KE INPUT
            const input = document.getElementById('livenessPassed');
            input.value = descriptorString;
            console.log('Input #livenessPassed di-set ke:', input.value.substring(0, 100) + '...');

            // Ambil foto dengan cropping
            takeVerifiedPhoto();
        } catch (err) {
            console.error('Error compute descriptor:', err);
            alert('Gagal menghitung descriptor wajah');
        }
    }

    function takeVerifiedPhoto() {
        // Buat canvas untuk cropping
        const c = document.createElement('canvas');
        c.width = 300; // Ukuran akhir kotak
        c.height = 300; // Ukuran akhir kotak
        const ctx = c.getContext('2d');

        // Hitung ukuran cropping
        const videoWidth = video.videoWidth;
        const videoHeight = video.videoHeight;
        const size = Math.min(videoWidth, videoHeight); // Ambil sisi terpendek
        const offsetX = (videoWidth - size) / 2;
        const offsetY = (videoHeight - size) / 2;

        // Draw gambar dengan cropping tengah
        ctx.drawImage(video, offsetX, offsetY, size, size, 0, 0, 300, 300);

        const dataUri = c.toDataURL('image/jpeg', 0.8);

        // Tampilkan hasil yang sudah di-crop
        document.getElementById('snapshotResult').innerHTML =
            `<div style="width: 300px; height: 300px; overflow: hidden; border-radius: 8px; margin: 0 auto;">
                <img src="${dataUri}" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>`;

        document.getElementById('faceData').value = dataUri;
        document.getElementById('results').classList.remove('hidden');
        document.getElementById('faceControls').style.display = 'flex';
    }

    function handleSubmit() {
        if (!livenessPassed) {
            alert('Liveness belum lolos!');
            return false;
        }

        const descriptorValue = document.getElementById('livenessPassed').value;
        if (!descriptorValue || descriptorValue === '0') {
            alert('Descriptor wajah belum dihitung!');
            return false;
        }

        const btn = document.getElementById('submitButton');
        btn.disabled = true;
        document.getElementById('buttonText').classList.add('hidden');
        document.getElementById('loadingSpinner').classList.remove('hidden');
        document.getElementById('face-form').submit();
    }

    window.onclick = e => {
        if (e.target === document.getElementById('faceModal')) closeModal();
    };
</script>

<script>
    function deleteClick(e) {
        let parentForm = e.parentElement;

        document.getElementById("delete-button").addEventListener("click", function() {
            parentForm.submit();
        });
    }
</script>