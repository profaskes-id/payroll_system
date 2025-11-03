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
            // Identitas karyawan
            'id_karyawan' => $this->integer()->notNull(),
            'nama' => $this->string()->notNull(),
            'id_bagian' => $this->integer()->null(),
            'nama_bagian' => $this->string()->null(),
            'jabatan' => $this->string()->null(),

            // Periode gaji
            'bulan' => $this->integer()->null(),
            'tahun' => $this->integer()->null(),
            'tanggal_awal' => $this->date()->null(),
            'tanggal_akhir' => $this->date()->null(),

            // Absensi dan keterlambatan
            'total_absensi' => $this->integer()->null(),
            'terlambat' => $this->time()->null(),
            'total_alfa_range' => $this->integer()->null(),

            // Gaji dan potongan
            'nominal_gaji' => $this->decimal(25, 10)->null(),
            'gaji_perhari' => $this->decimal(25, 10)->null(),
            'tunjangan_karyawan' => $this->decimal(25, 10)->null(),
            'potongan_karyawan' => $this->decimal(25, 10)->null(),
            'potongan_kasbon' => $this->decimal(25, 10)->null(),
            'potongan_terlambat' => $this->decimal(25, 10)->null(),
            'potongan_absensi' => $this->decimal(25, 10)->null(),

            // Lembur
            'jam_lembur' => $this->integer()->null(),
            'total_pendapatan_lembur' => $this->decimal(25, 10)->null(),

            // Dinas luar
            'dinas_luar_belum_terbayar' => $this->decimal(25, 10)->null(),

            // Audit Trail
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transaksi_gaji}}');
    }
}
