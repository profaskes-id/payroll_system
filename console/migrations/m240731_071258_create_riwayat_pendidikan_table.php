<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%riwayat_pendidikan}}`.
 */
class m240731_071258_create_riwayat_pendidikan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%riwayat_pendidikan}}', [
            'id_riwayat_pendidikan' => $this->primaryKey(), // Primary key with auto-increment
            'id_karyawan' => $this->integer()->notNull(), // Foreign key to karyawan table
            'jenjang_pendidikan' => $this->string()->notNull(),
            'institusi' => $this->string()->notNull(),
            'tahun_masuk' => $this->integer()->notNull(),
            'tahun_keluar' => $this->integer()->notNull(),
        ]);

        // Create index for foreign key
        $this->createIndex(
            'idx-riwayat_pendidikan-id_karyawan',
            '{{%riwayat_pendidikan}}',
            'id_karyawan'
        );

        $this->addForeignKey(
            'fk-riwayat_pendidikan-id_karyawan',
            '{{%riwayat_pendidikan}}',
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
        // Drop foreign key and index before dropping the table
        $this->dropForeignKey(
            'fk-riwayat_pendidikan-id_karyawan',
            '{{%riwayat_pendidikan}}'
        );

        $this->dropIndex(
            'idx-riwayat_pendidikan-id_karyawan',
            '{{%riwayat_pendidikan}}'
        );
        $this->dropTable('{{%riwayat_pendidikan}}');
    }
}
