<?php

use yii\helpers\Html;

$this->title = 'Notification';
?>

<div class="costume-container">
    <p class="">
        <?= Html::a('<i class="svgIcon fa fa-reply"></i> Back', ['/'], ['class' => 'costume-btn']) ?>
    </p>
</div>

<?php foreach ($messages as $message) : ?>
    <hr>

    <?php
    // Tentukan kelas border berdasarkan nilai data-isopen
    $borderClass = $message['is_open'] == 0 ? ' border-success shadow' : 'shadow ';
    ?>
    <div style="display: block; margin: 10px; width: 100%;">
        <form action="/panel/admin-notification/open-message" method="POST">
            <input type="hidden" name="messageId" value="<?= htmlspecialchars($message['id_message']); ?>">
            <input type="hidden" name="nama_transaksi" value="<?= htmlspecialchars($message['nama_transaksi']); ?>">
            <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($message['id_transaksi']); ?>">
            <button type="submit" data-isopen="<?= $message['is_open'] ?>" class="w-100" style="text-decoration: none; color: inherit; border: none; background: none; display: block;">

                <div class="alert w-100 text-start <?= $borderClass ?> " role="alert">
                    <h4 class="text-left alert-heading"><?= htmlspecialchars($message['judul']); ?></h4>
                    <p class="text-justify"><?= htmlspecialchars($message['deskripsi']); ?></p>
                    <p class="mb-0 text-sm text-left"><?= htmlspecialchars($message['create_at']); ?></p>
                </div>
            </button>
        </form>
    </div>
<?php endforeach; ?>
<style>
    .border-purple {
        border-color: purple !important;
        /* Mengatur warna border menjadi ungu */
        border-width: 2px;
        /* Anda bisa menyesuaikan ketebalan border */
        border-style: solid;
        /* Pastikan border terlihat */
    }
</style>