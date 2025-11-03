<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pending_kasbon}}`.
 */
class m251028_031114_create_pending_kasbon_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pending_kasbon}}', [
            'id_pending_kasbon' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'id_kasbon' => $this->integer()->notNull(),
            'id_periode_gaji' => $this->integer()->notNull(),
        ]);

        // 🔗 Relasi ke tabel karyawan
        $this->addForeignKey(
            'fk_pending_kasbon_karyawan',
            '{{%pending_kasbon}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        // 🔗 Relasi ke tabel pengajuan_kasbon
        $this->addForeignKey(
            'fk_pending_kasbon_kasbon',
            '{{%pending_kasbon}}',
            'id_kasbon',
            '{{%pengajuan_kasbon}}',
            'id_pengajuan_kasbon',
            'CASCADE'
        );

        // 🔗 Relasi ke tabel periode_gaji
        $this->addForeignKey(
            'fk_pending_kasbon_periode_gaji',
            '{{%pending_kasbon}}',
            'id_periode_gaji',
            '{{%periode_gaji}}',
            'id_periode_gaji',
            'CASCADE'
        );
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_pending_kasbon_periode_gaji', '{{%pending_kasbon}}');
        $this->dropForeignKey('fk_pending_kasbon_kasbon', '{{%pending_kasbon}}');
        $this->dropForeignKey('fk_pending_kasbon_karyawan', '{{%pending_kasbon}}');
        $this->dropTable('{{%pending_kasbon}}');
    }
}
