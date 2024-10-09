<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cetak}}`.
 */
class m241007_014158_create_cetak_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cetak}}', [
            'id_cetak' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'id_data_pekerjaan' => $this->integer()->notNull(),
            'nomor_surat' => $this->string()->notNull(),
            'tempat_dan_tanggal_surat' => $this->string()->notNull(),
            'nama_penanda_tangan' => $this->string()->notNull(),
            'jabatan_penanda_tangan' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'surat_upload' => $this->string()->null(),
        ]);
        //  Add foreign keys if needed
        $this->addForeignKey(
            'fk-cetak-karyawan',
            '{{%cetak}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cetak-pekerjaan',
            '{{%cetak}}',
            'id_data_pekerjaan',
            '{{%data_pekerjaan}}',
            'id_data_pekerjaan',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cetak-karyawan', '{{%cetak}}');
        $this->dropForeignKey('fk-cetak-pekerjaan', '{{%cetak}}');

        $this->dropTable('{{%cetak}}');
    }
}
