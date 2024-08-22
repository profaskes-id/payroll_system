<?php

use backend\models\PengajuanCuti;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backend\models\PengajuanCutiSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengajuan Cuti Karyawan';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(); ?>



<button style="width: 100%;" class="add-button" type="submit" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
    <i class="fas fa-search"></i>
    <span>
        Search
    </span>
</button>
<div style="margin-top: 10px;">
    <div class="collapse width" id="collapseWidthExample">
        <div class="" style="width: 100%;">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
</div>

<div class='table-container'>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'header' => Html::img(Yii::getAlias('@root') . '/images/icons/grid.svg', ['alt' => 'grid']),
                'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PengajuanCuti $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_pengajuan_cuti' => $model->id_pengajuan_cuti]);
                }
            ],
            [
                'label' => 'Karyawan',
                'value' => function ($model) {
                    return $model->karyawan->nama;
                }
            ],
            [
                'attribute' => 'tanggal_mulai',
                'headerOptions' => ['style' => 'width: 20%; text-align: center;'],
                'contentOptions' => ['style' => 'width: 20%; text-align: center;'],
            ],
            // 'tanggal_pengajuan',
            [
                'headerOptions' => ['style' => 'width: 30%; text-align: center;'],
                'attribute' => 'alasan_cuti',
                'format' => 'raw',
                'value' => function ($model) {
                    $words = explode(' ', $model->alasan_cuti);
                    $limitedWords = array_slice($words, 0, 5);
                    $truncatedText = implode(' ', $limitedWords);
                    return Html::encode($truncatedText) . '...';
                },
            ],
            // 'tanggal_selesai',
            [
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
                'format' => 'raw',
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusPengajuan->nama_kode ?? "<span class='text-danger'>master kode tidak aktif</span>";
                },
            ],
        ],
    ]); ?>
</div>

<?php Pjax::end(); ?>

</div>