<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\MasterCuti $model */

$this->title = 'Update Master Cuti: ' . $model->id_master_cuti;
$this->params['breadcrumbs'][] = ['label' => 'Master Cutis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_master_cuti, 'url' => ['view', 'id_master_cuti' => $model->id_master_cuti]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-cuti-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
