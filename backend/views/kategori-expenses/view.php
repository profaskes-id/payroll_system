<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\KategoriExpenses $model */

$this->title = $model->nama_kategori;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="kategori-expenses-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['update', 'id_kategori_expenses' => $model->id_kategori_expenses,], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_kategori_expenses' => $model->id_kategori_expenses,], [
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
                'nama_kategori',
                'deskripsi:ntext',
                [

                    'label' => 'Dibuat Pada',
                    'value' => function ($model) {
                        return $model->create_at ?? '';
                    }
                ],
                [
                    'label' => 'Dibuat Oleh',
                    'value' => function ($model) {
                        return $model->createBy->username ?? '';
                    }
                ],
                [

                    'label' => 'Diperbarui Pada',
                    'value' => function ($model) {
                        return $model->update_at ?? '';
                    }
                ],
                [
                    'label' => 'Diperbarui Oleh',
                    'value' => function ($model) {
                        return $model->updateBy->username ?? '';
                    }
                ],
            ],
        ]) ?>

    </div>
</div>