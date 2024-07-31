<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bagian}}`.
 */
class m240731_072536_create_bagian_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bagian}}', [
            'id_bagian' => $this->primaryKey(), // Primary key with auto-increment
            'nama_bagian' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bagian}}');
    }
}
