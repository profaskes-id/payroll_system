<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var amnah\yii2\user\models\forms\ForgotForm $model
 */

$this->title = Yii::t('user', 'Forgot password');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($flash = Yii::$app->session->getFlash('Forgot-success')): ?>

    <div class="alert alert-success">
        <p><?= $flash ?></p>
    </div>

<?php else: ?>

    <div style="position: absolute ; top: 50%; left: 50%; transform: translate(-50%, -50%);">

        <div class="card text-center" style="width: 300px; ">
            <div class="card-header h5 text-white bg-primary">Reset Password</div>
            <div class="card-body px-5">
                <p class="card-text py-2">
                    Masukkan alamat email Anda dan kami akan mengirimkan email dengan petunjuk untuk mengatur ulang kata sandi Anda.
                </p>
                <?php $form = ActiveForm::begin(['id' => 'forgot-form']); ?>
                <div data-mdb-input-init class="form-outline">
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email', 'type' => 'email', 'class' => 'form-control w-100']) ?>
                </div>
                <button type="submit" data-mdb-ripple-init class="btn btn-primary w-100">Reset password</button>
                <?php ActiveForm::end(); ?>
                <div class="d-flex justify-content-between mt-4">
                    <a class="" href="/panel/user/login">Kembali Ke Halaman Login ? </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>