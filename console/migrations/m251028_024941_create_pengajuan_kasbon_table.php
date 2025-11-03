<?php

use yii\db\Migration;

class m251028_024941_create_pengajuan_kasbon_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengajuan_kasbon}}', [
            'id_pengajuan_kasbon' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'gaji_pokok' => $this->decimal(15, 2)->defaultValue(0),
            'jumlah_kasbon' => $this->decimal(15, 2)->defaultValue(0),
            'tanggal_pengajuan' => $this->date()->notNull(),
            'tanggal_pencairan' => $this->date()->null(),
            'lama_cicilan' => $this->integer()->defaultValue(0),
            'angsuran_perbulan' => $this->decimal(15, 2)->defaultValue(0),
            'tanggal_mulai_potong' => $this->date()->null(),
            'keterangan' => $this->text()->null(),
            'tanggal_disetujui' => $this->date()->null(),
            'disetujui_oleh' => $this->integer()->null(),
            'tipe_potongan' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'created_by' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'status' => $this->smallInteger()->defaultValue(0),
        ]);

        // Foreign key ke tabel karyawan
        $this->addForeignKey(
            'fk_pengajuan_kasbon_karyawan',
            '{{%pengajuan_kasbon}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );
    }



    public function safeDown()
    {
        $this->dropForeignKey('fk_pengajuan_kasbon_disetujui_oleh', '{{%pengajuan_kasbon}}');
        $this->dropForeignKey('fk_pengajuan_kasbon_karyawan', '{{%pengajuan_kasbon}}');
        $this->dropTable('{{%pengajuan_kasbon}}');
    }
}
