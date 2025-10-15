<?php

use yii\db\Migration;

class m251015_072709_rekap_gaji_karyawan_pertransaksi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rekap_gaji_karyawan_pertransaksi}}', [
            'id_rekap_gaji_karyawan' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'nama_karyawan' => $this->string(100)->notNull(),
            'nama_bagian' => $this->string(100)->notNull(),
            'jabatan' => $this->string(100)->notNull(),
            'bulan' => $this->integer()->notNull(),
            'tahun' => $this->integer()->notNull(),
            'gaji_perbulan' => $this->decimal(15, 2)->notNull(),
            'gaji_perhari' => $this->decimal(15, 2)->notNull(),
            'gaji_perjam' => $this->decimal(15, 2)->notNull(),
        ]);
    }

    public function safeDown()
    {

        $this->dropTable('{{%rekap_gaji_karyawan_pertransaksi}}');
    }
}
