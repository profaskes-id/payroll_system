<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gaji_tunjangan}}`.
 */
class m241030_062856_create_gaji_tunjangan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%gaji_tunjangan}}', [
            'id_gaji_tunjangan' => $this->primaryKey(),
            'id_tunjangan_detail' => $this->integer()->notNull(),
            'nama_tunjangan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
        ]);

        // Jika ada foreign key untuk id_tunjangan_detail, uncomment berikut
        $this->addForeignKey(
            'fk-gaji_tunjangan-id_tunjangan_detail',
            'gaji_tunjangan',
            'id_tunjangan_detail',
            'tunjangan_detail',
            'id_tunjangan_detail', // Ganti dengan kolom yang sesuai di tabel tunjangan_detail
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-gaji_tunjangan-id_tunjangan_detail', 'gaji_tunjangan');
        $this->dropTable('{{%gaji_tunjangan}}');
    }
}
