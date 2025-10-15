<?php

use backend\models\helpers\KaryawanHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\JatahCutiKaryawan $model */

$id_karyawan = Yii::$app->request->get('id_karyawan');
$data = KaryawanHelper::getKaryawanById($id_karyawan);


$this->title = 'Jatah Cuti : ' . $data[0]['nama'];
$this->params['breadcrumbs'][] = ['label' => 'Jatah Cuti Karyawans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = $data[0]['nama'];

$tahun = Yii::$app->request->get()['tahun'] ?? date('Y');

?>
<div class="jatah-cuti-karyawan-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'tahun' => $tahun
    ]) ?>

</div>