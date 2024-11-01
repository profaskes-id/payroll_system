<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_wfh}}`.
 */
class m241023_185653_create_pengajuan_wfh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pengajuan_wfh', [
            'id_pengajuan_wfh' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'alasan' => $this->text()->notNull(),
            'lokasi' => $this->string()->null(),
            'alamat' => $this->text()->null(),
            'longitude' => $this->double()->notNull(),
            'latitude' => $this->double()->notNull(),
            'tanggal_array' => $this->text()->null(),
            'status' => $this->integer()->defaultValue(0)->notNull(),
            'disetujui_pada' => $this->dateTime()->null(),
            'disetujui_oleh' => $this->integer()->null(),
            'catatan_admin' => $this->text(),
        ]);

        $this->addForeignKey(
            'fk-pengajuan-wfh-karyawan',
            'pengajuan_wfh',
            'id_karyawan',
            'karyawan', // ganti dengan nama tabel karyawan
            'id_karyawan',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pengajuan_wfh}}');
        $this->dropForeignKey('fk-pengajuan-wfh-karyawan', 'pengajuan_wfh');
        $this->dropTable('pengajuan_wfh');
    }
}
