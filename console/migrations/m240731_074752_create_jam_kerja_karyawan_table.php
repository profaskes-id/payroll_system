<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jam_kerja_karyawan}}`.
 */
class m240731_074752_create_jam_kerja_karyawan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jam_kerja_karyawan}}', [
            'id_jam_kerja_karyawan' => $this->primaryKey(), // Primary key with auto-increment
            'id_karyawan' => $this->integer()->notNull(),
            'id_jam_kerja' => $this->integer()->notNull(),
            'jenis_shift' => $this->integer()->notNull(), // Shift type (integer)
        ]);

        // Create index for foreign key to jam_kerja
        $this->createIndex(
            'idx-jam_kerja_karyawan-id_jam_kerja',
            '{{%jam_kerja_karyawan}}',
            'id_jam_kerja'
        );

        // Add foreign key constraint to jam_kerja
        $this->addForeignKey(
            'fk-jam_kerja_karyawan-id_jam_kerja',
            '{{%jam_kerja_karyawan}}',
            'id_jam_kerja',
            '{{%jam_kerja}}',
            'id_jam_kerja',
            'CASCADE',
            'CASCADE'
        );

        // Create index for foreign key to karyawan
        $this->createIndex(
            'idx-jam_kerja_karyawan-id_karyawan',
            '{{%jam_kerja_karyawan}}',
            'id_karyawan'
        );

        // Add foreign key constraint to karyawan
        $this->addForeignKey(
            'fk-jam_kerja_karyawan-id_karyawan',
            '{{%jam_kerja_karyawan}}',
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
            'fk-jam_kerja_karyawan-id_karyawan',
            '{{%jam_kerja_karyawan}}'
        );
        $this->dropIndex(
            'idx-jam_kerja_karyawan-id_karyawan',
            '{{%jam_kerja_karyawan}}'
        );

        $this->dropForeignKey(
            'fk-jam_kerja_karyawan-id_jam_kerja',
            '{{%jam_kerja_karyawan}}'
        );
        $this->dropIndex(
            'idx-jam_kerja_karyawan-id_jam_kerja',
            '{{%jam_kerja_karyawan}}'
        );

        $this->dropTable('{{%jam_kerja_karyawan}}');
    }
}
