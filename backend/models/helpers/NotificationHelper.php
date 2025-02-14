<?php

namespace backend\models\helpers;

use Yii;
use backend\models\Message;
use backend\models\MessageReceiver;

class NotificationHelper
{
    /**
     * Mengirim notifikasi ke daftar pengguna tertentu.
     *
     * @param array $params Parameter notifikasi yang berisi:
     *                      - judul: Judul notifikasi
     *                      - deskripsi: Deskripsi notifikasi
     *                      - nama_transaksi: Nama transaksi
     *                      - id_transaksi: ID transaksi
     * @param array $adminUsers Daftar pengguna (admin) yang akan menerima notifikasi
     * @return bool
     * @throws \InvalidArgumentException Jika parameter tidak valid
     * @throws \RuntimeException Jika gagal menyimpan pesan atau penerima
     */
    public static function sendNotification($params = [], $adminUsers = [], $sender = null)
    {

        // Validasi parameter
        if (empty($params) || !isset($params['judul'], $params['deskripsi'], $params['nama_transaksi'], $params['id_transaksi'])) {
            throw new \InvalidArgumentException("Parameter array harus mengandung 'judul', 'deskripsi', 'nama_transaksi', dan 'id_transaksi'.");
        }

        // Validasi daftar admin
        if (empty($adminUsers)) {
            throw new \InvalidArgumentException("Daftar admin (penerima notifikasi) tidak boleh kosong.");
        }

        // Buat pesan baru
        $message = new Message();
        $message->sender = $sender['id'] ?? Yii::$app->user->identity->id;
        $message->judul = $params['judul']; // Ambil judul dari parameter
        $message->deskripsi = $params['deskripsi']; // Ambil deskripsi dari parameter
        $message->create_at = date('Y-m-d H:i:s');
        $message->nama_transaksi = $params['nama_transaksi']; // Ambil nama transaksi dari parameter
        $message->id_transaksi = $params['id_transaksi']; // Ambil ID transaksi dari parameter
        $message->create_by = $sender['id'] ?? Yii::$app->user->identity->id;

        // Simpan pesan
        if (!$message->save()) {
            throw new \RuntimeException("Gagal menyimpan pesan.");
        }

        // Simpan penerima di tabel message_receiver
        foreach ($adminUsers as $admin) {
            $messageReceiver = new MessageReceiver();
            $messageReceiver->message_id = $message->id_message;
            $messageReceiver->receiver_id = $admin->id; // Asumsikan $admin adalah objek User
            if (!$messageReceiver->save()) {
                throw new \RuntimeException("Gagal menyimpan penerima pesan.");
            }
        }

        return true; // Berhasil
    }
}

/* 


Jika penerima (receiver) bisa banyak (misalnya, semua admin dengan role 1 dan 3), maka struktur tabel yang Anda usulkan (dengan `receiver` sebagai array dan `is_open` sebagai array) **tidak ideal** dari sudut pandang normalisasi database. Database relasional dirancang untuk menghindari penyimpanan data dalam bentuk array atau JSON karena akan menyulitkan query dan manipulasi data.

Berikut adalah solusi yang lebih baik dan sesuai dengan prin    sip normalisasi database:

---

### **Struktur Tabel yang Direkomendasikan**

#### 1. **Tabel `message`**
Tabel ini menyimpan informasi dasar pesan, seperti pengirim (`sender`), judul, deskripsi, dan waktu pembuatan.

```php
$this->createTable('{{%message}}', [
    'id_message' => $this->primaryKey(),
    'sender' => $this->integer()->notNull(), // ID pengirim (karyawan)
    'judul' => $this->string(255)->notNull(),
    'deskripsi' => $this->text()->notNull(),
    'create_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
    'create_by' => $this->integer()->notNull(), // ID pembuat pesan
    'nama_transaksi' => $this->string(255),
    'id_transaksi' => $this->integer(),
]);
```

#### 2. **Tabel `message_receiver`**
Tabel ini menghubungkan pesan dengan penerima (receiver) dan menyimpan status `is_open` untuk setiap penerima.

```php
$this->createTable('{{%message_receiver}}', [
    'id' => $this->primaryKey(),
    'message_id' => $this->integer()->notNull(), // Foreign key ke tabel message
    'receiver_id' => $this->integer()->notNull(), // ID penerima (admin/super_admin)
    'is_open' => $this->smallInteger()->notNull()->defaultValue(0), // 0: belum dibuka, 1: sudah dibuka
    'open_at' => $this->timestamp(), // Waktu pesan dibuka
]);
```

#### 3. **Tabel `user`**
Tabel ini menyimpan data pengguna, termasuk role mereka.

```php
$this->createTable('{{%user}}', [
    'id' => $this->primaryKey(),
    'username' => $this->string(255)->notNull(),
    'role_id' => $this->integer()->notNull(), // 1: super_admin, 2: admin, 3: karyawan
    ...
]);
```

---

### **Cara Kerja**

1. **Mengirim Pesan ke Banyak Penerima:**
   - Saat mengirim pesan, Anda akan menyimpan data pesan di tabel `message`.
   - Kemudian, untuk setiap penerima (admin/super_admin), Anda akan menambahkan entri di tabel `message_receiver`.

   Contoh:
   ```php
   // Simpan pesan ke tabel message
   $message = new Message();
   $message->sender = 18; // ID karyawan
   $message->judul = 'Judul Pesan';
   $message->deskripsi = 'Deskripsi Pesan';
   $message->save();

   // Ambil semua admin (role_id = 1 atau 3)
   $adminUsers = User::find()->where(['role_id' => [1, 3]])->all();

   // Simpan penerima di tabel message_receiver
   foreach ($adminUsers as $admin) {
       $messageReceiver = new MessageReceiver();
       $messageReceiver->message_id = $message->id_message;
       $messageReceiver->receiver_id = $admin->id;
       $messageReceiver->save();
   }
   ```

2. **Mengecek Status `is_open`:**
   - Untuk mengecek apakah pesan sudah dibuka oleh penerima tertentu, Anda bisa query tabel `message_receiver`.

   Contoh:
   ```php
   // Cek status is_open untuk pesan tertentu dan penerima tertentu
   $status = MessageReceiver::find()
       ->where(['message_id' => $messageId, 'receiver_id' => $receiverId])
       ->one();

   if ($status->is_open == 1) {
       echo "Pesan sudah dibuka.";
   } else {
       echo "Pesan belum dibuka.";
   }
   ```

3. **Mengupdate Status `is_open`:**
   - Saat penerima membuka pesan, Anda akan mengupdate kolom `is_open` dan `open_at` di tabel `message_receiver`.

   Contoh:
   ```php
   $messageReceiver = MessageReceiver::find()
       ->where(['message_id' => $messageId, 'receiver_id' => $receiverId])
       ->one();

   $messageReceiver->is_open = 1;
   $messageReceiver->open_at = new \yii\db\Expression('NOW()');
   $messageReceiver->save();
   ```

---

### **Keuntungan Struktur Ini**

1. **Normalisasi Database:**
   - Data disimpan dalam bentuk terstruktur dan terpisah, sehingga memudahkan query dan maintenance.

2. **Fleksibilitas:**
   - Anda bisa menambahkan atau menghapus penerima tanpa mengubah struktur tabel.

3. **Kemudahan Query:**
   - Anda bisa dengan mudah mengecek status `is_open` untuk setiap penerima.

4. **Scalability:**
   - Struktur ini bisa dikembangkan lebih lanjut, misalnya dengan menambahkan kolom tambahan di tabel `message_receiver`.

---

### **Contoh Query Lain**

1. **Mengambil Semua Pesan untuk Penerima Tertentu:**
   ```php
   $messages = Message::find()
       ->innerJoin('message_receiver', 'message.id_message = message_receiver.message_id')
       ->where(['message_receiver.receiver_id' => $receiverId])
       ->all();
   ```

2. **Mengambil Semua Pesan yang Belum Dibuka oleh Penerima Tertentu:**
   ```php
   $unreadMessages = Message::find()
       ->innerJoin('message_receiver', 'message.id_message = message_receiver.message_id')
       ->where(['message_receiver.receiver_id' => $receiverId, 'message_receiver.is_open' => 0])
       ->all();
   ```

3. **Menghitung Jumlah Pesan yang Belum Dibuka oleh Penerima Tertentu:**
   ```php
   $unreadCount = MessageReceiver::find()
       ->where(['receiver_id' => $receiverId, 'is_open' => 0])
       ->count();
   ```

---

Dengan struktur ini, Anda bisa mengelola penerima pesan dan status `is_open` dengan lebih baik dan sesuai dengan prinsip database relasional.
*/