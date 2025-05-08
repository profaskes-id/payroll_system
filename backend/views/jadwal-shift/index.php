<?php

use backend\models\helpers\KaryawanHelper;
use backend\models\ShiftKerja;
use backend\models\Tanggal;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var backend\models\JadwalShiftSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Jadwal Shifts');
$this->params['breadcrumbs'][] = $this->title;
$tanggal = new Tanggal();
?>

<div class="jadwal-shift-index">

    <div class="costume-container">
        <p class=""><?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?></p>
    </div>

    <div class="table-container table-responsive">
        <table style="z-index: 99;" class="table position-relative table-bordered table-striped">
            <thead>
                <tr>
                    <th rowspan="2">Karyawan</th>
                    <th colspan="<?= count($dates) ?>">
                        <p class="text-center w-100 " style="font-size: 30px;">Jadwal Shift Bulan <?= $tanggal->getBulan(date('m'))  ?> <?php echo date('Y'); ?></p>
                    </th>
                </tr>
                <tr>
                    <?php foreach ($dates as $tgl): ?>
                        <th><?= $tgl ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($karyawanList as $iteration): ?>
                    <tr>
                        <td><?= $iteration['nama'] ?></td>
                        <?php foreach ($dates as $tgl): ?>
                            <?php
                            $shift = $iteration['shift'][$tgl]['nama_shift'] ?? '';
                            $shiftId = $shifts[$iteration['id_karyawan']][$tgl] ?? null;


                            $id_jadwal_shift =  $iteration['shift'][$tgl]['id_jadwal_shift'] ?? '';
                            // Contoh pewarnaan shift
                            $class = '';
                            if ($shiftId == 1) {
                                $class = 'text-white bg-success'; // Shift pagi
                            } elseif ($shiftId == 2) {
                                $class = 'bg-warning'; // Shift siang
                            } elseif ($shiftId == 3) {
                                $class = 'bg-info'; // Shift malam
                            } elseif ($shiftId == 4) {
                                $class = 'text-white bg-danger'; // Shift lembur
                            } elseif ($shiftId == 5) {
                                $class = 'text-white bg-secondary'; // Shift cadangan
                            } elseif ($shiftId == 6) {
                                $class = 'text-white bg-primary'; // Shift khusus
                            } else {
                                $class = 'bg-light'; // Default jika tidak dikenali
                            }

                            ?>

                            <td class="clickable-cell <?= $class ?>"
                                data-id_jadwal_shift="<?= $id_jadwal_shift ?>"
                                data-id_karyawan="<?= $iteration['id_karyawan'] ?>"
                                data-tanggal="<?= $tgl ?>"
                                data-id_shift_kerja="<?= $shiftId ?>"
                                style="cursor: pointer;"><?= $shift ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
Modal::begin([
    'title' => 'Update Shift',
    'id' => 'shift-modal',
    'size' => 'modal-lg',
]);
?>


<div class="jadwal-shift-form table-container">

    <!-- Form Update -->
    <?php $form = ActiveForm::begin(['action' => ['jadwal-shift/update'], 'method' => 'post']); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>

    <div class="row">
        <div class="col-12">
            <?php
            $nama_group = \yii\helpers\ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama');
            echo $form->field($model, 'id_karyawan')->widget(Select2::classname(), [
                'data' => $nama_group,
                'language' => 'id',
                'options' => ['placeholder' => 'Pilih karyawan ...'],
                'pluginOptions' => ['allowClear' => true],
            ])->label('Karyawan');
            ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'tanggal')->input('date', ['id' => 'tanggal-awal']) ?>
        </div>

        <div class="col-12">
            <?php
            $nama_kode = \yii\helpers\ArrayHelper::map(ShiftKerja::find()->asArray()->all(), 'id_shift_kerja', 'nama_shift');
            echo $form->field($model, 'id_shift_kerja')->widget(Select2::classname(), [
                'data' => $nama_kode,
                'language' => 'id',
                'options' => ['placeholder' => 'Cari Nama Shift'],
                'pluginOptions' => ['allowClear' => true],
            ])->label('Shift Kerja');
            ?>
        </div>

        <div class="form-group col-12">
            <div class="row">
                <div class="col-3">
                    <button class="add-button " type="submit">
                        <span>Update</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <!-- End Form Update -->

    <!-- Form Delete -->
    <?php $deleteForm = ActiveForm::begin(['action' => ['jadwal-shift/delete'], 'method' => 'post']); ?>

    <?= $deleteForm->field($model, 'id')->hiddenInput(['id' => 'id_jadwal_shift_delete'])->label(false); ?>

    <div class="row">
        <div class="col-3">
            <button class="reset-button " type="submit">
                <span>Delete</span>
            </button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <!-- End Form Delete -->

</div>

<?php Modal::end(); ?>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // When a cell with the class .clickable-cell is clicked
        $('.clickable-cell').click(function(e) {
            e.preventDefault(); // Prevent default action if any

            // Get data attributes from the clicked cell
            var id_jadwal_shift = $(this).data('id_jadwal_shift');
            var namaKaryawan = $(this).data('id_karyawan');
            var tanggal = $(this).data('tanggal'); // Get the day value (e.g., "01")
            var id_shift_kerja = $(this).data('id_shift_kerja');

            // Get the current month and year
            var currentDate = new Date();
            var month = currentDate.getMonth() + 1; // Months are 0-based in JavaScript
            var year = currentDate.getFullYear();

            // Format the month and day to ensure 2 digits (e.g., "04" for April, "01" for the first day)
            if (month < 10) month = '0' + month; // Add leading zero if month is single digit
            // if (tanggal < 10) tanggal = '0' + tanggal; // Add leading zero if tanggal is single digit

            // Combine the day, month, and year to form a full date
            var fullDate = year + '-' + month + '-' + tanggal;
            console.info(id_jadwal_shift);

            // Show the modal
            $('#shift-modal').modal('show');


            $('#shift-modal input[name="JadwalShift[id]"]').val(id_jadwal_shift);


            // Populate the form fields with the retrieved values
            $('#shift-modal #tanggal-awal').val(fullDate); // Set the date field

            // Set the selected Karyawan using Select2
            $('#shift-modal select[name="JadwalShift[id_karyawan]"]').val(namaKaryawan).trigger('change');

            // Set the selected Shift Kerja using Select2
            $('#shift-modal select[name="JadwalShift[id_shift_kerja]"]').val(id_shift_kerja).trigger('change'); // Use shiftId if it corresponds to the shift
        });
    });
</script>