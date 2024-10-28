<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%total_hari_kerja}}`.
 */
class m241023_191311_create_total_hari_kerja_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('total_hari_kerja', [
            'id_total_hari_kerja' => $this->primaryKey(),
            'id_jam_kerja' => $this->integer()->notNull(),
            'total_hari' => $this->integer()->notNull(),
            'bulan' => $this->integer()->notNull(),
            'tahun' => $this->integer()->notNull(),
            'keterangan' => $this->string()->null(),
            'is_aktif' => $this->smallInteger()->defaultValue(1),
        ]);

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-jam_kerja',
            'total_hari_kerja',
            'id_jam_kerja',
            'jam_kerja',
            'id_jam_kerja',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jam_kerja', 'total_hari_kerja');
        $this->dropForeignKey('fk-user', 'total_hari_kerja');
        $this->dropTable('total_hari_kerja');
    }
}
