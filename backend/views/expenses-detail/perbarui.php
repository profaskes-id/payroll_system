<?php

use backend\models\KategoriExpenses;
use backend\models\SubkategoriExpenses;
use kartik\select2\Select2;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesDetail $model */

$subkategoriData = yii\helpers\ArrayHelper::map(SubkategoriExpenses::find()->all(), 'id_kategori_expenses', 'nama_subkategori');
?>

<div class="expenses-detail-form ">

    <?php $form = ActiveForm::begin(); ?>
    <div class="table-container">
        <div class="row">

            <div class="col-md-3 ">
                <?= $form->field($headerEx, 'id_expense_header')->textInput(['id' => 'id_expense_header', 'readonly' => true, 'value' => $nextKode ?? Yii::$app->request->get('id_expense_header')])->label('Kode Transaksi') ?>
            </div>

            <div class="col-md-3">
                    <?= $form->field($headerEx, 'tanggal')->textInput([ 'readonly' => true, 'type' => 'date', ])->label('Tanggal Transaksi') ?>
            </div>
        </div>
        <br>
        <h5>Input detail pengeluaran</h5>

        <div class="row">

            <div class="col-12 col-md-5">
                <?php 
                $nama_group = \yii\helpers\ArrayHelper::map(KategoriExpenses::find()->all(), 'id_kategori_expenses', 'nama_kategori');
                echo $form->field($model, 'id_kategori_expenses')->widget(kartik\select2\Select2::classname(), [
                    'data' => $nama_group,
                    'language' => 'id',
                    'options' => ['placeholder' => 'Cari Kategori  ...', 'id' => 'kategori-expenses'],
                    'pluginOptions' => [
                        'tags' => true,
                        'allowClear' => true
                    ],
                ])->label('Kategori Pengeluaran');
                ?>

                <a href="/panel/kategori-expenses/index" target="_blank" style="transform:translateY(-10px); display:inline-block;  position:relative; color:green;  ">
                    Tambah Kategori +  
                </a>
            </div>

            <div class="col-12 col-md-5">
                <?php
                $subkat = SubkategoriExpenses::find()->where(['id_subkategori_expenses' => $model->id_subkategori_expenses])->asArray()->one();
                $name_group = \yii\helpers\ArrayHelper::map(SubkategoriExpenses::find()->all(), 'id_subkategori_expenses', 'nama_subkategori');
                echo $form->field($model, 'id_subkategori_expenses')->widget(Select2::classname(), [
                    'data' => $name_group,
                    'language' => 'id',
                    'options' => ['readonly' => false, 'id' => 'subKat', 'placeholder' => 'Pilih Sub Kategori ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Tipe Pengeluaran');
                ?>
                     <a href="/panel/subkategori-expenses/index" target="_blank" style="transform:translateY(-10px); display:inline-block;  position:relative; color:green;  ">
                    Tambah Sub Kategori +  
                </a>
            </div>


            <div class="col-md-5">
                <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true, 'type' => 'number']) ?>
            </div>


            <div class="col-md-5">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 1]) ?>
            </div>
        </div>
        <div class="form-group">
            <button class="add-button" type="submit">
                <span>
                    Save
                </span>
            </button>
        </div>
    </div>







    <?php 
    

    ActiveForm::end(); ?>

    <?php if (!empty($dataHEader)): ?>


        <br>
        <div class="table-container">

            <table class="table table-bordered">
                <tr>
                    <th>Kategori</th>
                    <th>Tipe Pengeluaran</th>
                    <th>jumlah</th>
                    <th>keterangan</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($dataHEader as $data) : ?>
                    <tr>

                        <td><?= $data['nama_kategori'] ?></td>
                        <td><?= $data['nama_subkategori'] ?></td>
                        <td><?=   'Rp. ' . number_format($data['jumlah'], 2, ',', '.') ?></td>
                        <td><?= $data['keterangan'] ?></td>
                        <td style="width: 100px">
                            <!-- Tombol Hapus -->
                            <form action="<?= \yii\helpers\Url::to(['expenses-detail/delete', 'id_expense_detail' => $data['id_expense_detail']]) ?>" method="post" style="display:inline;">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                                <button type="submit" class="reset-button" onclick="return confirm('Apakah Anda yakin ingin menghapus?');"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    <?php endif; ?>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {



            $('#kategori-expenses').change(function(e) {
                e.preventDefault();
                let valueProp = this.value;

                $.ajax({
                    url: '/panel/expenses-detail/sub-get',
                    type: 'GET',
                    data: {
                        id: valueProp
                    },
                    success: function(data) {
                        let kabupatenSelect = $('#subKat');
                        kabupatenSelect.empty(); // Kosongkan pilihan yang ada
                        kabupatenSelect.append('<option></option>'); // Tambahkan opsi kosong untuk allowClear

                        $.each(data, function(key, value) {
                            kabupatenSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        kabupatenSelect.trigger('change'); // Trigger change untuk memperbarui Select2
                    }
                });
            });

        });
    </script>

</div>