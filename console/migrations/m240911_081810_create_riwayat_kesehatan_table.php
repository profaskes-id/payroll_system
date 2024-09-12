<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%riwayat_kesehatan}}`.
 */
class m240911_081810_create_riwayat_kesehatan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%riwayat_kesehatan}}', [

            'id_riwayat_kesehatan' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'nama_pengecekan' => $this->string()->notNull(),
            'keterangan' => $this->text()->null(),
            'surat_dokter' => $this->string()->null(),
            'tanggal' => $this->date()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-riwayat-kesehatan-karyawan',
            '{{%riwayat_kesehatan}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-riwayat-kesehatan-karyawan',
            '{{%riwayat_kesehatan}}'
        );

        $this->dropTable('{{%riwayat_kesehatan}}');
    }
}
