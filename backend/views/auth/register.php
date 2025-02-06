<?php

use Codeception\Lib\Interfaces\Web;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title =  'Register';
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
    .add-button {
        border: none;
        display: flex;
        padding: 0.75rem 1.5rem;
        background-color: #488aec;
        color: #ffffff;
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        vertical-align: middle;
        align-items: center;
        border-radius: 0.5rem;
        user-select: none;
        gap: 0.65rem;
        box-shadow: 0 4px 6px -1px #488aec31, 0 2px 4px -1px #488aec17;
        transition: all .6s ease;
    }
</style>

<?php $this->head(); ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@root') . '/css/login.css' ?>">
<section class="ftco-section">
    <div class="row justify-content-center align-items-center">
        <div class="col-12">
            <div class="wrap">
                <?= Html::img('@root/images/banner/banner-image-2.jpg', ['class' => 'img', 'style' => 'position:relative; left:-200px']) ?>
                <div class="p-4 login-wrap px-md-5 pt-md-4">

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
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control w-100">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control w-100">
                        <input type="checkbox" id="show-password"> Show Password
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#show-password').change(function() {
            var passwordField = $('#password');
            var confirmPasswordField = $('#confirm_password');
            if ($(this).is(':checked')) {
                passwordField.attr('type', 'text');
                confirmPasswordField.attr('type', 'text');
            } else {
                passwordField.attr('type', 'password');
                confirmPasswordField.attr('type', 'password');
            }
        });

        $('#confirm_password').on('input', function() {
            var password = $('#password').val();
            var confirmPassword = $(this).val();
            if (confirmPassword !== password) {
                $(this).css('border-color', '');
            } else {
                $(this).css('border-color', '');
            }
        });
    });
</script>