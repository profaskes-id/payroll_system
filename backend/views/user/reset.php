<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var amnah\yii2\user\models\User $user
 * @var bool $success
 * @var bool $invalidToken
 */

$this->title = Yii::t('user', 'Reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-reset">


    <?php if (!empty($success)): ?>

        <div class="alert alert-success">

            <p><?= Yii::t("user", "Password has been reset") ?></p>
            <p><?= Html::a(Yii::t("user", "Log in here"), ["/user/login"]) ?></p>

        </div>

    <?php elseif (!empty($invalidToken)): ?>

        <div class="alert alert-danger">
            <p><?= Yii::t("user", "Invalid token") ?></p>
        </div>

    <?php else: ?>
        <div style="position: absolute ; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="card text-center" style="width: 300px;">
                <div class="card-header h5 text-white bg-primary">Password Baru</div>
                <div class="card-body px-5">
                    <p class="card-text py-2">
                        Email : <?= $user->email ?>
                    </p>
                    <?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>

                    <?= $form->field($user, 'newPassword')->passwordInput()->label('Password Baru') ?>
                    <?= $form->field($user, 'newPasswordConfirm')->passwordInput()->label('Konfirmasi Password Baru') ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t("user", "Update Password"), ['class' => 'btn btn-primary w-100']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>