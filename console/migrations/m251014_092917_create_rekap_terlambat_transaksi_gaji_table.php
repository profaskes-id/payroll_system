<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rekap_terlambat_transaksi_gaji}}`.
 */
class m251014_092917_create_rekap_terlambat_transaksi_gaji_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rekap_terlambat_transaksi_gaji}}', [
            'id_rekap_terlambat' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'bulan' => $this->integer()->null(),
            'tahun' => $this->integer()->null(),
            'nama' => $this->string(255)->notNull(),
            'tanggal' => $this->date()->notNull(),
            'lama_terlambat' => $this->string(8)->notNull(), // Format HH:MM:SS
            'potongan_permenit' => $this->decimal(10, 2)->notNull(), // Bisa disesuaikan precision-nya
        ]);

        // Optional: Tambahkan index untuk pencarian yang lebih cepat
        $this->createIndex(
            'idx-rekap_terlambat-id_karyawan',
            '{{%rekap_terlambat_transaksi_gaji}}',
            'id_karyawan'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rekap_terlambat_transaksi_gaji}}');
    }
}
