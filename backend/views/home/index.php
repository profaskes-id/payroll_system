<?php

use backend\assets\AppAsset;
use backend\models\Absensi;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Absensis';

$this->params['breadcrumbs'][] = $this->title;

?>


<?= $this->render('@backend/views/components/_header'); ?>




<section class="grid grid-cols-10  relative overflow-x-hidden min-h-[90dvh] justify-center gap-5 content-start">


    <div class="col-span-10 rounded-md mx-5 h-40 bg-blue-600 "></div>
    <div class="col-span-5 rounded-md mx-5 h-40 bg-blue-600 "></div>
    <div class="col-span-5 rounded-md mx-5 h-40 bg-blue-600 "></div>

    <div class="fixed w-1/2 bottom-0 left-1/2 -translate-x-1/2 z-40 hidden lg:block  ">
        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>



</section>

<div class="lg:hidden">

    <?= $this->render('@backend/views/components/_footer'); ?>
</div>


<footer class="hidden lg:block text-center text-black my-20">
    <p>Copyright &copy; 2024 Profaskes</p>
</footer>