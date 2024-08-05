<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Perusahaan $model */

$this->title = $model->id_perusahaan;
$this->params['breadcrumbs'][] = ['label' => 'perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="perusahaan-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class='table-container'>

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_perusahaan' => $model->id_perusahaan], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_perusahaan' => $model->id_perusahaan], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_perusahaan',
                'nama_perusahaan',
                'status_perusahaan',
            ],
        ]) ?>
    </div>

</div>