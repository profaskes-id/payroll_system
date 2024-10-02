<?php

use Codeception\Lib\Interfaces\Web;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title =  'Register';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $this->head(); ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@root') . '/css/login.css' ?>">
<section class="ftco-section">
    <div class="row justify-content-center align-items-center">
        <div class="col-12">
            <div class="wrap">
                <?= Html::img('@root/images/banner/banner-image-2.jpg', ['class' => 'img', 'style' => 'position:relative; left:-200px']) ?>
                <div class="login-wrap p-4 px-md-5 pt-md-4">


                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            // 'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                            // 'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]); ?>
                    <div class="form-group">
                        <label for="kode_karyawan">Kode Karyawan </label>
                        <input readonly value="<?= $kode_karyawan  ?>" type="text" id="kode_karyawan" name="kode_karyawan" class="form-control w-100">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control w-100">
                    </div>

                    <div class="form-group">
                        <button class="add-button" type="submit">
                            <span>
                                Submit
                            </span>
                        </button>
                    </div>
                    <div class="form-group ">
                        <div class="text-left">
                            <div class=" text-md-right">
                                <a href="/panel/user/forgot">Lupa Password</a>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                        <!-- <p class="text-center">Belum Punya Akun ? <a data-toggle="tab" href="/klinik-member" style="color: #1e70b8; text-decoration: underline;">Daftar Sekarang</a></p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>