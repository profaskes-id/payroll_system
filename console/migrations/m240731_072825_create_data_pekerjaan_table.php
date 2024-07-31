<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%data_pekerjaan}}`.
 */
class m240731_072825_create_data_pekerjaan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%data_pekerjaan}}', [
            'id_data_pekerjaan' => $this->primaryKey(), // Primary key with auto-increment
            'id_karyawan' => $this->integer()->notNull(), // Foreign key to karyawan table
            'id_bagian' => $this->integer()->notNull(), // Foreign key to bagian table
            'dari' => $this->date()->notNull(), // Start date
            'sampai' => $this->date(), // End date (nullable)
            'status' => $this->string()->notNull(), // Status (e.g., aktif, non-aktif)
            'jabatan' => $this->string()->notNull(), // Position
        ]);

        // Create index for foreign key to karyawan
        $this->createIndex(
            'idx-data_pekerjaan-id_karyawan',
            '{{%data_pekerjaan}}',
            'id_karyawan'
        );

        // Add foreign key constraint to karyawan
        $this->addForeignKey(
            'fk-data_pekerjaan-id_karyawan',
            '{{%data_pekerjaan}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );

        // Create index for foreign key to bagian
        $this->createIndex(
            'idx-data_pekerjaan-id_bagian',
            '{{%data_pekerjaan}}',
            'id_bagian'
        );

        // Add foreign key constraint to bagian
        $this->addForeignKey(
            'fk-data_pekerjaan-id_bagian',
            '{{%data_pekerjaan}}',
            'id_bagian',
            '{{%bagian}}',
            'id_bagian',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key and index for id_karyawan
        $this->dropForeignKey(
            'fk-data_pekerjaan-id_karyawan',
            '{{%data_pekerjaan}}'
        );

        $this->dropIndex(
            'idx-data_pekerjaan-id_karyawan',
            '{{%data_pekerjaan}}'
        );

        // Drop foreign key and index for id_bagian
        $this->dropForeignKey(
            'fk-data_pekerjaan-id_bagian',
            '{{%data_pekerjaan}}'
        );

        $this->dropIndex(
            'idx-data_pekerjaan-id_bagian',
            '{{%data_pekerjaan}}'
        );

        // Drop the table
        $this->dropTable('{{%data_pekerjaan}}');
    }
}
