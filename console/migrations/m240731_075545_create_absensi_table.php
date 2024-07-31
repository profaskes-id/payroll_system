<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%absensi}}`.
 */
class m240731_075545_create_absensi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%absensi}}', [
            'id_absensi' => $this->primaryKey(), // Primary key with auto-increment
            'id_karyawan' => $this->integer()->notNull(), // Foreign key to karyawan table
            'id_jam_kerja' => $this->integer()->notNull(), // Foreign key to jam_kerja table
            'tanggal' => $this->date()->notNull(), // Date of attendance
            'hari' => $this->string()->notNull(), // Day of the week (e.g., Monday)
            'jam_masuk' => $this->time(), // Time of arrival (nullable)
            'jam_pulang' => $this->time(), // Time of departure (nullable)
            'kode_status_hadir' => $this->string()->notNull(), // Status code (e.g., present, absent)
        ]);

        // Create index for foreign key to karyawan
        $this->createIndex(
            'idx-absensi-id_karyawan',
            '{{%absensi}}',
            'id_karyawan'
        );

        // Add foreign key constraint to karyawan
        $this->addForeignKey(
            'fk-absensi-id_karyawan',
            '{{%absensi}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );

        // Create index for foreign key to jam_kerja
        $this->createIndex(
            'idx-absensi-id_jam_kerja',
            '{{%absensi}}',
            'id_jam_kerja'
        );

        // Add foreign key constraint to jam_kerja
        $this->addForeignKey(
            'fk-absensi-id_jam_kerja',
            '{{%absensi}}',
            'id_jam_kerja',
            '{{%jam_kerja}}',
            'id_jam_kerja',
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
            'fk-absensi-id_karyawan',
            '{{%absensi}}'
        );

        $this->dropIndex(
            'idx-absensi-id_karyawan',
            '{{%absensi}}'
        );

        // Drop foreign key and index for id_jam_kerja
        $this->dropForeignKey(
            'fk-absensi-id_jam_kerja',
            '{{%absensi}}'
        );

        $this->dropIndex(
            'idx-absensi-id_jam_kerja',
            '{{%absensi}}'
        );

        // Drop the table
        $this->dropTable('{{%absensi}}');
    }
}
