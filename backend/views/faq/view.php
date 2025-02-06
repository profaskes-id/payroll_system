<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Faq $model */

$this->title = 'FAQ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="faq-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

    <div class="table-container table-responsive">


        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

            <p class="d-flex justify-content-start " style="gap: 10px;">
                <?= Html::a('Update', ['update', 'id_faq' => $model->id_faq], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'id_faq' => $model->id_faq], [
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
        'question:ntext',
        'answer:ntext',
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->status === 1 
                    ? '<span class=" text-success"> Aktif</span>' 
                    : '<span class=" text-danger">Tidak Aktif</span>';
            },
        ],
    ],
    'options' => [
        'class' => 'table table-striped table-bordered detail-view'
    ],
    'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
]) ?>

        </div>
    </div>
</div>