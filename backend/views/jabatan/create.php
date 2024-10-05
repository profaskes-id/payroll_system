<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */

$this->title = Yii::t('app', 'Tambah Jabatan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jabatan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-create">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'nama_group' => $nama_group
    ]) ?>

</div>