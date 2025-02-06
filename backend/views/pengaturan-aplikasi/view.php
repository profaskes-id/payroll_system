<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\PengaturanAplikasi $model */

$this->title = $model->title_sidebar;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengaturan Aplikasis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengaturan-aplikasi-view">
<div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>
    <div class="table-container table-responsive">
        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Apakah Anda Yakin Ingin Menghapus Item Ini ?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'logo_sidebar',
            'title_sidebar',
            'subtitle_sidebar',
            'logo_login',
            'backround_login',
        ],
    ]) ?>

</div>
</div>
