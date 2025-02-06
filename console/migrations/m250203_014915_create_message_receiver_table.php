<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message_receiver}}`.
 */
class m250203_014915_create_message_receiver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message_receiver}}', [
            'id' => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(), // Foreign key ke tabel message
            'receiver_id' => $this->integer()->notNull(), // ID penerima (admin/super_admin)
            'is_open' => $this->smallInteger()->notNull()->defaultValue(0), // 0: belum dibuka, 1: sudah dibuka
            'open_at' => $this->timestamp(), // Waktu pesan dibuka
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message_receiver}}');
    }
}
