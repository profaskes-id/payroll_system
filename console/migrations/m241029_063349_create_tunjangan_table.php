<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tunjangan}}`.
 */
class m241029_063349_create_tunjangan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tunjangan', [
            'id_tunjangan' => $this->primaryKey(),
            'nama_tunjangan' => $this->string()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(),
            'satuan' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tunjangan}}');
    }
}
