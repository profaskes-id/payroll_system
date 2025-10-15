<?php

use yii\db\Migration;

class m251014_050831_create_tunjangan_potongan_rekap_gaji_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // === Tabel tunjangan_rekap_gaji ===
        $this->createTable('{{%tunjangan_rekap_gaji}}', [
            'id_tunjangan_rekap_gaji' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'bulan' => $this->integer()->null(),
            'tahun' => $this->integer()->null(),
            'id_tunjangan' => $this->integer()->notNull(),
            'nama_tunjangan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(12, 2)->notNull(),
        ]);


        // === Tabel potongan_rekap_gaji ===
        $this->createTable('{{%potongan_rekap_gaji}}', [
            'id_potongan_rekap_gaji' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'bulan' => $this->integer()->null(),
            'tahun' => $this->integer()->null(),
            'id_potongan' => $this->integer()->notNull(),
            'nama_potongan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(12, 2)->notNull(),
        ]);

        // Optional: Tambahkan index untuk pencarian yang lebih cepat
        $this->createIndex(
            'idx-tunjangan_rekap_gaji-id_karyawan',
            '{{%tunjangan_rekap_gaji}}',
            'id_karyawan'
        );

        $this->createIndex(
            'idx-potongan_rekap_gaji-id_karyawan',
            '{{%potongan_rekap_gaji}}',
            'id_karyawan'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%potongan_rekap_gaji}}');

        $this->dropTable('{{%tunjangan_rekap_gaji}}');
    }
}
