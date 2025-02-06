<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaksi_gaji}}`.
 */
class m241030_064925_create_transaksi_gaji_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaksi_gaji}}', [
            'id_transaksi_gaji' => $this->primaryKey(),
            'kode_karyawan' => $this->string()->notNull(),
            'nomer_identitas' => $this->string()->notNull(),
            'nama' => $this->string()->notNull(),
            'bagian' => $this->string()->notNull(),
            'jabatan' => $this->string()->notNull(),
            'jam_kerja' => $this->integer()->notNull(),
            'status_karyawan' => $this->string()->notNull(),
            'periode_gaji' => $this->integer()->null(),
            'jumlah_hari_kerja' => $this->integer()->notNull(),
            'jumlah_hadir' => $this->integer()->notNull(),
            'jumlah_sakit' => $this->integer()->notNull(),
            'jumlah_wfh' => $this->integer()->notNull(),
            'jumlah_cuti' => $this->integer()->notNull(),
            'gaji_pokok' => $this->decimal(10, 2)->notNull(),
            'jumlah_jam_lembur' => $this->time()->notNull(),
            'lembur_perjam' => $this->decimal(10, 2)->notNull(),
            'total_lembur' => $this->decimal(10, 2)->notNull(),
            'jumlah_tunjangan' => $this->decimal(10, 2)->notNull(),
            'jumlah_potongan' => $this->decimal(10, 2)->notNull(),
            'potongan_wfh_hari' => $this->decimal(10, 2)->notNull(),
            'jumlah_potongan_wfh' => $this->decimal(10, 2)->notNull(),
            'jumlah_tidak_hadir' => $this->integer()->notNull()->defaultValue(0),
            'potongan_tidak_hadir_hari' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'jumlah_potongan_tidak_hadir' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'jumlah_terlambat'  => $this->time()->notNull(),
            'potongan_terlambat_permenit' => $this->decimal(10, 2)->notNull(),
            'jumlah_potongan_terlambat' => $this->decimal(10, 2)->notNull(),
            'gaji_diterima' => $this->decimal(10, 2)->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transaksi_gaji-periode_gaji', 'transaksi_gaji');

        $this->dropTable('{{%transaksi_gaji}}');
    }
}
