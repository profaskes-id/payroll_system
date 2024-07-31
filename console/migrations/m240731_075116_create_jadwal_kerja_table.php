<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jadwal_kerja}}`.
 */
class m240731_075116_create_jadwal_kerja_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jadwal_kerja}}', [
            'id_jadwal_kerja' => $this->primaryKey(), // Primary key with auto-increment
            'id_jam_kerja' => $this->integer()->notNull(), // Foreign key to jam_kerja table
            'nama_hari' => $this->string()->notNull(), // Day of the week (e.g., Monday)
            'jam_masuk' => $this->time()->notNull(), // Time when work starts
            'jam_keluar' => $this->time()->notNull(), // Time when work ends
            'lama_istirahat' => $this->integer()->notNull(), // Duration of break in minutes
            'jumlah_jam' => $this->integer()->notNull(), // Total hours worked
        ]);

        // Create index for foreign key to jam_kerja
        $this->createIndex(
            'idx-jadwal_kerja-id_jam_kerja',
            '{{%jadwal_kerja}}',
            'id_jam_kerja'
        );

        // Add foreign key constraint to jam_kerja
        $this->addForeignKey(
            'fk-jadwal_kerja-id_jam_kerja',
            '{{%jadwal_kerja}}',
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
        // Drop foreign key and index before dropping the table
        $this->dropForeignKey(
            'fk-jadwal_kerja-id_jam_kerja',
            '{{%jadwal_kerja}}'
        );

        $this->dropIndex(
            'idx-jadwal_kerja-id_jam_kerja',
            '{{%jadwal_kerja}}'
        );

        $this->dropTable('{{%jadwal_kerja}}');
    }
}
