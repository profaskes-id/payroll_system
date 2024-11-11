<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var amnah\yii2\user\models\User $user
 */

$this->title = $user->username ?? 'User';
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="table-container table-responsive">

        <div class="costume-container">
            <p class="">
                <?= Html::a('<i class="svgIcon fa  fa-reply"></i> Back', ['index'], ['class' => 'costume-btn']) ?>
            </p>
        </div>

        <p class="d-flex justify-content-start " style="gap: 10px;">
            <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $user->id], ['class' => 'add-button']) ?>
            <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $user->id], [
                'class' => 'reset-button',
                'data' => [
                    'confirm' => Yii::t('user', 'Apakah Anda Yakin Ingin Menghapus Item Ini ?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'id',
                'role_id',
                'status',
                'email:email',
                'username',
                'profile.full_name',
                'password',
                'auth_key',
                'access_token',
                'logged_in_ip',
                'logged_in_at',
                'created_ip',
                'created_at',
                'updated_at',
                'banned_at',
                'banned_reason',
            ],
        ]) ?>
    </div>

</div>