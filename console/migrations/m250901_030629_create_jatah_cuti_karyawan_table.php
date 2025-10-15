<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jatah_cuti_karyawan}}`.
 */
class m250901_030629_create_jatah_cuti_karyawan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jatah_cuti_karyawan}}', [
            'id_jatah_cuti' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'id_master_cuti' => $this->integer()->notNull(),
            'jatah_hari_cuti' => $this->integer()->notNull()->defaultValue(0),
            'tahun' => $this->integer()->notNull(),

            'created_at' => $this->integer()->null(),
            'created_by' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ]);

        // Foreign key ke tabel karyawan
        $this->addForeignKey(
            'fk-jatah_cuti_karyawan-id_karyawan',
            '{{%jatah_cuti_karyawan}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );

        // Foreign key ke tabel master_cuti
        $this->addForeignKey(
            'fk-jatah_cuti_karyawan-id_master_cuti',
            '{{%jatah_cuti_karyawan}}',
            'id_master_cuti',
            '{{%master_cuti}}',
            'id_master_cuti',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jatah_cuti_karyawan-id_karyawan', '{{%jatah_cuti_karyawan}}');
        $this->dropForeignKey('fk-jatah_cuti_karyawan-id_master_cuti', '{{%jatah_cuti_karyawan}}');

        // Hapus tabel
        $this->dropTable('{{%jatah_cuti_karyawan}}');
    }
}
