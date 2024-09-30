    <?php

    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /** @var yii\web\View $this */
    /** @var backend\models\Bagian $model */

    $this->title = $model->nama_bagian;
    $this->params['breadcrumbs'][] = ['label' => 'bagian', 'url' => ['/perusahaan/view', 'id_perusahaan' => $model->perusahaan->id_perusahaan]];
    $this->params['breadcrumbs'][] = $this->title;
    \yii\web\YiiAsset::register($this);
    ?>
    <div class="bagian-view">


        <div class="costume-container">
            <p class="">
                <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['/perusahaan/view', 'id_perusahaan' => $model->perusahaan->id_perusahaan], ['class' => 'costume-btn']) ?>
            </p>
        </div>


        <div class='table-container'>
            <p class="d-flex justify-content-start " style="gap: 10px;">
                <?= Html::a('Update', ['update', 'id_bagian' => $model->id_bagian,], ['class' => 'add-button']) ?>
                <?= Html::a('Delete', ['delete', 'id_bagian' => $model->id_bagian,], [
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
                    'nama_bagian',
                    [
                        'label' => 'perusahaan',
                        'value' => function ($model) {
                            return $model->perusahaan->nama_perusahaan;
                        }
                    ],
                ],
            ]) ?>
        </div>

    </div>