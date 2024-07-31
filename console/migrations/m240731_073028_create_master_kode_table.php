<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%master_kode}}`.
 */
class m240731_073028_create_master_kode_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%master_kode}}', [
            'nama_group' => $this->string()->notNull(),
            'kode' => $this->string()->notNull(),
            'nama_kode' => $this->string()->notNull(),

            // Define composite primary key
            'PRIMARY KEY (nama_group, kode)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%master_kode}}');
    }
}
