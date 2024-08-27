<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use amnah\yii2\user\helpers\Timezone;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var amnah\yii2\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-profile">



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



    <section class=" mx-auto container  px-5 my-3 ">

        <div class="mb-4 border-b bg-white ">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Profile</button>
                </li>
                <li class="me-2" role="presentation">
                    <a href="/panel/user/account">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Akun</button>
                    </a>
                </li>

            </ul>
        </div>
        <div class=" mb-4 border-b px-4 py-4 rounded-lg bg-gray-50">


            <?php if ($flash = Yii::$app->session->getFlash("Profile-success")):
            ?>
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Berhasil</span> . <?php echo $flash ?>
                </div>

            <?php endif;
            ?>

            <?php $form = ActiveForm::begin([
                'id' => 'profile-form',
                'options' => ['class' => 'form-horizontal'],
                'enableAjaxValidation' => true,
            ]); ?>

            <div class="grid grid-cols-12 gap-y-4 ">


                <div class="col-span-12 ">
                    <?= $form->field($profile, 'full_name')->textInput(['class' => 'rounded-md border-gray-300  w-full   ']) ?>
                </div>


                <div class="col-span-12">
                    <?= $form->field($profile, 'timezone')->dropDownList(ArrayHelper::map(Timezone::getAll(), 'identifier', 'name'), ['class' => 'w-full my-2 rounded-md border-gray-300   ']); ?>
                </div>
            </div>

            <div class="">
                <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'add-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </section>
    <div class="fixed bottom-0 left-0 right-0 z-50">
        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>
</div>