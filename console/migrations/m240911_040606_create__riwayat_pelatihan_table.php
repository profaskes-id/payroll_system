<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%_riwayat_pelatihan}}`.
 */
class m240911_040606_create__riwayat_pelatihan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%riwayat_pelatihan}}', [
            'id_riwayat_pelatihan' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'judul_pelatihan' => $this->string()->notNull(),
            'tanggal_mulai' => $this->date()->notNull(),
            'tanggal_selesai' => $this->date()->notNull(),
            'penyelenggara' => $this->string()->notNull(),
            'deskripsi' => $this->text(),
            'sertifikat' => $this->string(),
        ]);




        // Menambahkan foreign key constraint untuk kolom `id_karyawan`
        $this->addForeignKey(
            'fk-riwayat_pelatihan-id_karyawan',
            'riwayat_pelatihan',
            'id_karyawan',
            'karyawan', // Nama tabel karyawan
            'id_karyawan',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-riwayat_pelatihan-id_karyawan',
            'riwayat_pelatihan'
        );

        $this->dropIndex(
            'idx-riwayat_pelatihan-id_karyawan',
            'riwayat_pelatihan'
        );
        $this->dropTable('{{%riwayat_pelatihan}}');
    }
}
