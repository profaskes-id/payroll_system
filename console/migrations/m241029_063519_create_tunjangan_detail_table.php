<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tunjangan_detail}}`.
 */
class m241029_063519_create_tunjangan_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tunjangan_detail}}', [
            'id_tunjangan_detail' => $this->primaryKey(),
            'id_tunjangan' => $this->integer()->notNull(),
            'id_karyawan' => $this->integer()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
            'status' => $this->smallInteger()->defaultValue(0),

        ]);

        $this->addForeignKey(
            'fk-tunjangan_detail-id_tunjangan',
            'tunjangan_detail',
            'id_tunjangan',
            'tunjangan',
            'id_tunjangan',
            'CASCADE',
            'CASCADE'
        );

        // Menambahkan foreign key untuk id_karyawan
        $this->addForeignKey(
            'fk-tunjangan_detail-id_karyawan',
            'tunjangan_detail',
            'id_karyawan',
            'karyawan',
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
        $this->dropForeignKey('fk-tunjangan_detail-id_tunjangan', 'tunjangan_detail');
        $this->dropForeignKey('fk-tunjangan_detail-id_karyawan', 'tunjangan_detail');

        $this->dropTable('tunjangan_detail');
    }
}
