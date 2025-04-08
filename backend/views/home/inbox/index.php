<div class="p-3">
    <?= $this->render('@backend/views/components/_header', ['link' => '/panel/home', 'title' => 'notifikasi']); ?>

    <?php foreach ($messages as $message) : ?>
        <hr>
        <?php
        // Tentukan kelas border berdasarkan nilai data-isopen
        $borderClass = $message['is_open'] == 0 ? ' border-green-500 bg-green-50' : ' ';
        $textClass = $message['is_open'] == 0 ? ' text-green-800' : ' ';
        ?>
        <div>

            <div style="display: block; margin: 10px; width: 100%;" class="w-full">
                <form action="/panel/home/open-message" method="POST">
                    <input type="hidden" name="messageId" value="<?= htmlspecialchars($message['id_message']); ?>">
                    <input type="hidden" name="nama_transaksi" value="<?= htmlspecialchars($message['nama_transaksi']); ?>">
                    <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($message['id_transaksi']); ?>">
                    <button type="submit" class="relative z-30" data-isopen="<?= $message['is_open'] ?>" class="w-full " style="cursor:pointer; text-decoration: none; color: inherit; border: none; background: none; display: block;">
                        <div role="alert" class="p-4  <?= $borderClass ?> rounded-sm border-s-4 flex flex-col items-start justify-start  ">
                            <strong class="text-start font-medium  <?= $textClass ?>"> <?= $message['judul']; ?> </strong>
                            <p class="mt-2 text-sm text-start <?= $textClass ?>">
                                <?= $message['deskripsi']; ?>
                            </p>

                            <hr>
                            <p class="mt-2 text-sm <?= $textClass ?>">
                                <?= $message['create_at']; ?>
                            </p>

                        </div>
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>