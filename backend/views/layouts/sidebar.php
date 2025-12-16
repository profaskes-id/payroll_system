<?php

use backend\models\IzinPulangCepat;
use hail812\adminlte\widgets\Menu;
use mdm\admin\components\MenuHelper;
use yii\helpers\Html;
?>

<?php
$jumlahPulangCepatToday = IzinPulangCepat::find()->where(['tanggal' => date('Y-m-d'), 'status' => 0])->count();

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #131133 !important; ">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex align-items-center justify-content-center">
            <div class="image">
                <img src="<?= Yii::getAlias('@root') ?>/images/logo.svg" alt="Logo" class="brand-image img-circle " style="width: 60px; ">
            </div>
            <div class="info">
                <a href="#" style="font-size: 17.8px;" class="text-white d-block fw-bold"><?= Yii::$app->params['APPLICATION_BUSINESS'] ?></a>
                <a href="#" style="font-size:14px" class="d-block">Payroll System</a>
            </div>
        </div>

        <div class="px-5 mx-auto d-flex justify-content-center align-items-center w-100">
            <?= Html::a('<i class="fa fa-solid fa-user"></i>', ['/user/profile'], ['class' => 'nav-link']) ?>
            <?= Html::a('<i class="fa fa-solid fa-cog"></i>', ['/user/account'], ['class' => 'nav-link']) ?>
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/user/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </div>
        <hr style="background-color: white; margin: 0; padding: 0;" />
        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="items-end justify-center mt-2 d-flex flex-column ">
            <nav class="">
                <?php

                echo Menu::widget([
                    'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id),
                    'encodeLabels' => false,
                ]);
                ?>
            </nav>


        </div>
    </div>
</aside>