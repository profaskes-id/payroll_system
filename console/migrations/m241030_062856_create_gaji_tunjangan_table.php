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
            'id_transaksi_gaji' => $this->integer()->notNull(),
            'id_tunjangan_detail' => $this->integer()->notNull(),
            'nama_tunjangan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gaji_tunjangan}}');
    }
}
