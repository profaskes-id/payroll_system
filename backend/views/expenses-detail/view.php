<?php

use backend\models\Tanggal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\ExpensesDetail $model */

$tanggal = new Tanggal();
$this->title = $model->kategoriExpenses->nama_kategori . " {$tanggal->getIndonesiaFormatTanggal($model['expenseHeader']['tanggal'])}";
$this->params['breadcrumbs'][] = ['label' => 'Expenses Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="expenses-detail-view">

    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
        </p>
    </div>


    <div class="table-container table-responsive">

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a('Update', ['perbarui', 'id_expense_detail' => $model->id_expense_detail,], ['class' => 'add-button']) ?>
            <?= Html::a('Delete', ['delete', 'id_expense_detail' => $model->id_expense_detail,], [
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

                [
                    'label' => "Header",
                    'value' => function ($model) {
                        return $model->id_expense_header ?? '';
                    }
                ],
                [
                    'label' => "Tanggal",
                    'value' => function ($model) use ($tanggal)  {
                        
                        return $tanggal->getIndonesiaFormatTanggal($model->expenseHeader['tanggal'])  ?? '';
                    }
                ],
                [
                    'label' => "Kategori Pengeluaran",
                    'value' => function ($model) {
                        return $model->kategoriExpenses->nama_kategori ?? '';
                    }
                ],
                [
                    'label' => "Tipe Pengeluaran",
                    'value' => function ($model) {
                        return $model->subkategoriExpenses->nama_subkategori ?? '';
                    }
                ],
                'jumlah',
                'keterangan:ntext',

            ],
        ]) ?>

    </div>
</div>