<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Bagian $model */

$this->title = $model->id_bagian;
$this->params['breadcrumbs'][] = ['label' => 'Bagians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bagian-view">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class='table-container'>
        <p>
            <?= Html::a('Update', ['update', 'id_bagian' => $model->id_bagian], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id_bagian' => $model->id_bagian], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_bagian',
                'nama_bagian',
            ],
        ]) ?>
    </div>

</div>