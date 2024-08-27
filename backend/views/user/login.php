<?php

use Codeception\Lib\Interfaces\Web;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('user', 'Login');
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
                    <div class="form-group ">
                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control w-100']) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control w-100']) ?>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control btn  rounded submit px-3" style="background-color: #1e70b8; color: white; font-weight: 800; font-size: 18px;">Masuk</button>
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