<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gaji_potongan}}`.
 */
class m241030_063045_create_gaji_potongan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%gaji_potongan}}', [
            'id_gaji_potongan' => $this->primaryKey(),
            'id_potongan_detail' => $this->integer()->notNull(),
            'nama_potongan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-gaji_potongan-id_potongan_detail',
            'gaji_potongan',
            'id_potongan_detail',
            'potongan_detail',
            'id_potongan_detail', // Ganti dengan kolom yang sesuai di tabel potongan_detail
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-gaji_potongan-id_potongan_detail', 'gaji_potongan');
        $this->dropTable('{{%gaji_potongan}}');
    }
}
