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
            'id_transaksi_gaji' => $this->integer()->notNull(),
            'id_potongan_detail' => $this->integer()->notNull(),
            'nama_potongan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gaji_potongan}}');
    }
}
