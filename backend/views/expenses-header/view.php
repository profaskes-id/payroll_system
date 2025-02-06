<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesHeader $model */

$this->title =  $model->tanggal;
$this->params['breadcrumbs'][] = ['label' => 'Expenses Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="expenses-header-view">
    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_expense_header' => $model->id_expense_header,], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_expense_header' => $model->id_expense_header,], [
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
                'tanggal',
                'create_at',
                [
                    'label' => 'Create By',
                    'value' => function ($model) {
                        return $model->createBy->username;
                    }
                ],
                'update_at',
                [
                    'label' => 'Update By',
                    'value' => function ($model) {
                        return $model->updateBy->username;
                    }
                ],
            ],
        ]) ?>

    </div>