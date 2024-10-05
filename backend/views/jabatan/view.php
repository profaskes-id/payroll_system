<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\MasterKode $model */

$this->title = $model->nama_group;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Kodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-kode-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">


        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

            <p class="d-flex justify-content-start " style="gap: 10px;">
                <?= Html::a('Update', ['update', 'nama_group' => $model->nama_group, 'kode' => $model->kode], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'nama_group' => $model->nama_group, 'kode' => $model->kode], [
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
                    'nama_group',
                    'kode',
                    'nama_kode',
                    'status',
                    'urutan',
                ],
            ]) ?>

        </div>
    </div>
</div>