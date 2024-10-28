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
            'tanggal' => $this->date()->notNull(), // Date of attendance
            'jam_masuk' => $this->time(), // Time of arrival (nullable)
            'jam_pulang' => $this->time()->defaultValue(null), // Time of departure (nullable)
            'kode_status_hadir' => $this->string()->notNull(), // Status code (e.g., present, absent)
            'keterangan' => $this->text()->defaultValue(null), // Status description (nullable)
            'lampiran' => $this->string()->defaultValue(null), // Attachment (nullable)
            'latitude' => $this->decimal(9, 6)->defaultValue(null), // Latitude with decimal precision
            'longitude' => $this->decimal(9, 6)->defaultValue(null), // Longitude with decimal precision
            'alasan_terlambat' => $this->text()->defaultValue(null), // Status description (nullable)
            'is_lembur' => $this->smallInteger()->defaultValue(0), // Status description (nullable)
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


        // Drop the table
        $this->dropTable('{{%absensi}}');
    }
}
