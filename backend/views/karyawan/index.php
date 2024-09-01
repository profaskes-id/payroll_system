<?php

use backend\models\Karyawan;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\KaryawanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Karyawan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="karyawan-index">


    <div class="costume-container">
        <p class="">
            <?= Html::a('<i class="svgIcon fa fa-regular fa-plus"></i> Add New', ['create'], ['class' => 'costume-btn']) ?>
        </p>
    </div>

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


    <div class="table-container">
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
                    'urlCreator' => function ($action, Karyawan $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_karyawan' => $model->id_karyawan]);
                    }
                ],
                'nama',
                [
                    'headerOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 10%; text-align: center;'],
                    'label' => 'KODE',
                    'value' => 'kode_karyawan',
                ],
                [
                    'label' => 'Jenis Kelamin',
                    'value' => function ($model) {
                        return $model->kode_jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan';
                    }
                ],
                [
                    'label' => 'Divisi',
                    'value' => function ($model) {
                        $divisiAktif = [];
                        // return $model->data->nama_kode;
                        $filteredData = array_filter($model->dataPekerjaans, function ($item) {
                            return $item->is_aktif == 1;
                        });
                        foreach ($filteredData as $key => $value) {
                            $divisiAktif[] = $value->bagian->nama_bagian;
                        }
                        // return implode(', ', $divisiAktif);
                        return implode(', ', $divisiAktif);
                    }
                ],
                [
                    'header' => 'Invite',
                    'headerOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'contentOptions' => ['style' => 'width: 5%; text-align: center;'],
                    'value' => function ($model) {
                        $isUser = User::find()->where(['email' => $model->email]);
                        if (!$isUser->exists()) {
                            return Html::a('<i class="fas fa-user-plus"></i>', ['invite', 'id_karyawan' => $model->id_karyawan], [
                                'title' => 'Invite',
                                'data-pjax' => '0',
                            ]);
                        } else {
                            return "<p>{$isUser->one()->email}</p>";
                        }
                    },
                    'format' => 'raw',
                ],

            ],
        ]); ?>
    </div>


</div>