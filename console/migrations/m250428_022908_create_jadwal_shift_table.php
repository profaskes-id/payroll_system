<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jadwal_shift}}`.
 */
class m250428_022908_create_jadwal_shift_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jadwal_shift}}', [
            'id_jadwal_shift' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'id_shift_kerja' => $this->integer()->notNull(),
        ]);

        // FK ke tabel karyawan
        $this->addForeignKey(
            'fk-jadwal_shift-id_karyawan',
            '{{%jadwal_shift}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        // FK ke tabel shift_kerja
        $this->addForeignKey(
            'fk-jadwal_shift-id_shift_kerja',
            '{{%jadwal_shift}}',
            'id_shift_kerja',
            '{{%shift_kerja}}',
            'id_shift_kerja',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jadwal_shift-id_karyawan', '{{%jadwal_shift}}');
        $this->dropForeignKey('fk-jadwal_shift-id_shift_kerja', '{{%jadwal_shift}}');

        $this->dropTable('{{%jadwal_shift}}');
    }
}
