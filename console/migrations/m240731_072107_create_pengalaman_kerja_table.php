<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengalaman_kerja}}`.
 */
class m240731_072107_create_pengalaman_kerja_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengalaman_kerja}}', [
            'id_pengalaman_kerja' => $this->primaryKey(), // Primary key with auto-increment
            'id_karyawan' => $this->integer()->notNull(), // Foreign key to karyawan table
            'perusahaan' => $this->string()->notNull(),
            'posisi' => $this->string()->notNull(),
            'masuk_pada' => $this->date()->notNull(),
            'keluar_pada' => $this->date()->notNull(),
        ]);

        // Create index for foreign key
        $this->createIndex(
            'idx-pengalaman_kerja-id_karyawan',
            '{{%pengalaman_kerja}}',
            'id_karyawan'
        );

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-pengalaman_kerja-id_karyawan',
            '{{%pengalaman_kerja}}',
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
            'fk-pengalaman_kerja-id_karyawan',
            '{{%pengalaman_kerja}}'
        );

        $this->dropIndex(
            'idx-pengalaman_kerja-id_karyawan',
            '{{%pengalaman_kerja}}'
        );
        $this->dropTable('{{%pengalaman_kerja}}');
    }
}
