<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use amnah\yii2\user\helpers\Timezone;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var amnah\yii2\user\Module $module
 * @var amnah\yii2\user\models\User $user
 * @var amnah\yii2\user\models\UserToken $userToken
 */

$module = $this->context->module;


$this->title = Yii::t('user', 'Profile');
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

    .help-block {
        color: red;
    }

    .small {
        font-size: 12px;
        color: gray;
    }
</style>



<section class=" mx-auto container  px-5 my-3">

    <div class="mb-4 border-b border-gray-200 dark:border-gray-700 ">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <a href="/panel/user/profile">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg">Profile</button>
                </a>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Akun</button>
            </li>

        </ul>
    </div>
    <div id="default-tab-content">
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">


        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
            <div class="user-default-account">


                <?php if ($flash = Yii::$app->session->getFlash("Account-success")): ?>

                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <p><?= $flash ?></p>
                    </div>

                <?php elseif ($flash = Yii::$app->session->getFlash("Resend-success")): ?>

                    <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400" role="alert">
                        <p><?= $flash ?></p>
                    </div>

                <?php elseif ($flash = Yii::$app->session->getFlash("Cancel-success")): ?>

                    <div class="p-4 mb-4 text-sm text-rose-800 rounded-lg bg-rose-50 dark:bg-gray-800 dark:text-rose-400" role="alert">
                        <p><?= $flash ?></p>
                    </div>

                <?php endif; ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'account-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                        'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                ]); ?>


                <div class="grid grid-cols-1 gap-6 mt-5">

                    <div>
                        <?php if ($user->password): ?>
                            <?= $form->field($user, 'currentPassword')->passwordInput(['class' => ' rounded-md border-gray-300 w-full  '])->label('Password Saat Ini') ?>
                        <?php endif ?>
                    </div>
                </div>


                <!-- <hr /> -->

                <div class="mt-4">
                    <?php if ($module->useEmail): ?>
                        <?= $form->field($user, 'email')->textInput(['type' => 'email', 'class' => ' rounded-md border-gray-300 w-full ']) ?>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">

                        <?php if (!empty($userToken->data)): ?>

                            <p class="small"><?= Yii::t('user', "Pending email confirmation: [ {newEmail} ]", ["newEmail" => $userToken->data]) ?></p>
                            <p class="small">
                                <?= Html::a(Yii::t("user", "Resend"), ["/user/resend-change"]) ?> / <?= Html::a(Yii::t("user", "Cancel"), ["/user/cancel"]) ?>
                            </p>

                        <?php elseif ($module->emailConfirmation): ?>

                            <p class="small"><?= Yii::t('user', 'Changing your email requires email confirmation') ?></p>

                        <?php endif; ?>

                    </div>
                </div>


                <div class="mt-4">
                    <?php if ($module->useUsername): ?>
                        <?= $form->field($user, 'username')->textInput(['class' => ' rounded-md border-gray-300 w-full ']) ?>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <?= $form->field($user, 'newPassword')->passwordInput(['class' => ' rounded-md border-gray-300 w-full ']) ?>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'add-button my-5']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <?php foreach ($user->userAuths as $userAuth): ?>
                            <p>Linked Social Account: <?= ucfirst($userAuth->provider) ?> / <?= $userAuth->provider_id ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Settings tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
        </div>
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
            <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Contacts tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
        </div>
    </div>
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>
</section>