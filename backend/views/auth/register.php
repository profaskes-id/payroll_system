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
                        <label for="confirm-password">Konfirmasi Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" class="form-control w-100">
                        <small id="error-message" style="color: red; display: none;">Password tidak cocok!</small>
                    </div>




                    <div class="form-group">
                        <button onclick="validatePasswords()" type="submit" class="form-control btn  rounded submit px-3" style="background-color: #1e70b8; color: white; font-weight: 800; font-size: 18px;">Masuk</button>
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
<script>
    function validatePasswords() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const errorMessage = document.getElementById('error-message');

        if (password !== confirmPassword) {
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
            // Form can be submitted here, or any other action you want to perform
            // alert('Password valid!');
            document.getElementById('login-form').submit();
        }
    }
</script>