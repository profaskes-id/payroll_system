<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m250203_013531_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%message}}', [
            'id_message' => $this->primaryKey(),
            'sender' => $this->integer()->notNull(), // ID pengirim (karyawan)
            'judul' => $this->string(255)->notNull(),
            'deskripsi' => $this->text()->notNull(),
            'create_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'create_by' => $this->integer()->notNull(), // ID pembuat pesan
            'nama_transaksi' => $this->string(255),
            'id_transaksi' => $this->integer(),
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%message}}');
    }
}
