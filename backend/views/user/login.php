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
                <div class="login-wrap p-4 px-md-5 pt-md-4">
                    <?= Html::img('@root/images/profaskes.png', ['width' => '250px', 'class' => 'mb-4 mx-auto', 'alt' => 'banner']) ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [],
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>