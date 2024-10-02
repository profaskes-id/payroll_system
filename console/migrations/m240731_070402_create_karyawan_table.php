<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%karyawan}}`.
 */
class m240731_070402_create_karyawan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%karyawan}}', [
            'id_karyawan' => $this->primaryKey(),
            'kode_karyawan' => $this->string()->notNull(),

            // Data pribadi
            'nama' => $this->string()->notNull(),
            'nomer_identitas' => $this->string()->notNull()->unique(),
            'jenis_identitas' => $this->integer()->notNull(), // 1. KTP, 2. KIS

            'kode_jenis_kelamin' => $this->integer()->notNull(), // 1. Laki-laki, 2. Perempuan
            'tempat_lahir' => $this->string()->notNull(),
            'tanggal_lahir' => $this->date()->notNull(),
            'status_nikah' => $this->integer()->notNull(), // 1. Belum kawin, 2. Sudah kawin, 3. Cerai hidup, 4. Cerai mati
            'agama' => $this->string()->notNull(), // Islam, Katolik, Hindu, Buddha, Konghucu
            'suku' => $this->string()->null(), // Jawa, Madura, Aceh

            // Kontak
            'email' => $this->string()->notNull()->unique(),
            'nomer_telepon' => $this->string()->notNull(),
            'foto' => $this->string()->null(),
            'ktp' => $this->string()->null(),
            'cv' => $this->string()->null(),
            'ijazah_terakhir' => $this->string()->null(),

            // Data alamat
            'kode_negara' => $this->string()->notNull(),

            // Alamat Identitas
            'kode_provinsi_identitas' => $this->string()->notNull(),
            'kode_kabupaten_kota_identitas' => $this->string()->notNull(),
            'kode_kecamatan_identitas' => $this->string()->notNull(),
            'desa_lurah_identitas' => $this->string()->notNull(),
            'alamat_identitas' => $this->text()->notNull(),
            'rt_identitas' => $this->string()->null(),
            'rw_identitas' => $this->string()->null(),
            'kode_post_identitas' => $this->string()->null(),

            // Apakah alamat identitas sama dengan alamat domisili
            'is_current_domisili' => $this->smallInteger()->notNull()->defaultValue(0),

            // Alamat Domisili
            'kode_provinsi_domisili' => $this->string()->null(),
            'kode_kabupaten_kota_domisili' => $this->string()->null(),
            'kode_kecamatan_domisili' => $this->string()->null(),
            'desa_lurah_domisili' => $this->string()->null(),
            'alamat_domisili' => $this->text()->null(),
            'rt_domisili' => $this->string()->null(),
            'rw_domisili' => $this->string()->null(),
            'kode_post_domisili' => $this->string()->null(),

            'informasi_lain' => $this->text()->notNull(),

            'is_invite' => $this->smallInteger()->notNull()->defaultValue(0),
            'invite_at' => $this->date()->null(),
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%karyawan}}');
    }
}
