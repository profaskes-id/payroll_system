<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%data_keluarga}}`.
 */
class m240731_072333_create_data_keluarga_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%data_keluarga}}', [
            'id_data_keluarga' => $this->primaryKey(), // Primary key with auto-increment
            'id_karyawan' => $this->integer()->notNull(), // Foreign key to karyawan table
            'nama_anggota_keluarga' => $this->string()->notNull(),
            'hubungan' => $this->string()->notNull(),
            'pekerjaan' => $this->string()->notNull(),
            'tahun_lahir' => $this->integer()->notNull(),
        ]);

        // Create index for foreign key
        $this->createIndex(
            'idx-data_keluarga-id_karyawan',
            '{{%data_keluarga}}',
            'id_karyawan'
        );

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-data_keluarga-id_karyawan',
            '{{%data_keluarga}}',
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
            'fk-data_keluarga-id_karyawan',
            '{{%data_keluarga}}'
        );

        $this->dropIndex(
            'idx-data_keluarga-id_karyawan',
            '{{%data_keluarga}}'
        );

        $this->dropTable('{{%data_keluarga}}');
    }
}
