<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%potongan}}`.
 */
class m241029_063752_create_potongan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%potongan}}', [
            'id_potongan' => $this->primaryKey(),
            'nama_potongan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
            'satuan' => $this->string()->notNull(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%potongan}}');
    }
}
