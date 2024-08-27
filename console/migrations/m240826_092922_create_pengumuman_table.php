<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengumuman}}`.
 */
class m240826_092922_create_pengumuman_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengumuman}}', [
            'id_pengumuman' => $this->primaryKey(),
            'judul' => $this->string()->notNull(),
            'deskripsi' => $this->text()->notNull(),
            'dibuat_pada' => $this->date()->notNull(),
            'update_pada' => $this->date()->null(),
            'dibuat_oleh' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pengumuman}}');
    }
}
