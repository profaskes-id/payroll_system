<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\helpers\KaryawanHelper;
use backend\models\MasterKode;
use kartik\select2\Select2;
use yii\bootstrap4\Modal;


$tanggalAwal = MasterKode::find()->where(['nama_group' => "tanggal-cut-of"])->one();
$bulan = date('m');
$tahun = date('Y');
$firstDayOfMonth = date('Y-m-d', mktime(0, 0, 0, $bulan, intval($tanggalAwal->nama_kode) + 1, $tahun));
$lastdate = date('Y-m-d', mktime(0, 0, 0, $bulan + 1, intval($tanggalAwal->nama_kode), $tahun));
$tanggal_awal =  Yii::$app->request->get() == [] ? $firstDayOfMonth :  Yii::$app->request->get()['tanggal_awal'];
$tanggal_akhir =  Yii::$app->request->get() == [] ? $lastdate :  Yii::$app->request->get()['tanggal_akhir'];

$id_karyawan = Yii::$app->request->get('id_karyawan');

if (!empty($id_karyawan)) {
    $model->id_karyawan = is_array($id_karyawan) ? $id_karyawan : [$id_karyawan];
} else {
    $model->id_karyawan = [];
}

?>

<?php
$form = ActiveForm::begin([
    'action' => ['exel-all'],
    'method' => 'get',
    'options' => ['target' => '_blank'] // Download di tab baru
]); ?>



<!-- Modal -->
<?php
Modal::begin([
    'id' => 'exportModal',
    'size' => Modal::SIZE_LARGE,
]);
?>
<!-- Tanggal Awal & Akhir (Manual HTML input) -->
<div class="row">
    <div class="col-md-6">
        <label for="tanggal_awal">Tanggal Awal</label>
        <?= Html::input('date', 'tanggal_awal', $tanggal_awal, ['class' => 'form-control', 'required' => true]) ?>

    </div>
    <div class="col-md-6">
        <label for="tanggal_akhir">Tanggal Akhir</label>
        <?= Html::input('date', 'tanggal_akhir', $tanggal_akhir, ['class' => 'form-control', 'required' => true]) ?>
    </div>
</div>
<div class="mt-3 form-group">
    <?= $form->field($model, 'id_karyawan')->widget(Select2::class, [
        'data' => ArrayHelper::map(KaryawanHelper::getKaryawanData(), 'id_karyawan', 'nama'),
        'language' => 'id',
        'options' => [
            'placeholder' => 'Pilih Karyawan ...',
            'multiple' => true,
            'id' => 'id_karyawan',
            'name' => 'Karyawan[id_karyawan][]', // penting untuk multiple select
        ],
        'pluginOptions' => [
            'tags' => false,
            'closeOnSelect' => false,
        ],
    ])->label('Pilih Karyawan ') ?>

</div>

<div class="form-group">
    <button class="add-button" type="submit">
        <i class="fa fa-table"></i>
        <span>Export</span>
    </button>
</div>

<?php Modal::end(); ?>
<?php ActiveForm::end(); ?>




<?php
$js = <<<JS
    $('.save-button').on('click', function () {
    setTimeout(function () {
        window.location.href = '/panel/rekap-per-karyawan/';
    }, 2000);
});

JS;
$this->registerJs($js);
?>