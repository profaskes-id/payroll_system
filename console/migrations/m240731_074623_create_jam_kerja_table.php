<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jam_kerja}}`.
 */
class m240731_074623_create_jam_kerja_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jam_kerja}}', [
            'id_jam_kerja' => $this->primaryKey(),
            'nama_jam_kerja' => $this->string()->notNull(),
            'jenis_shift' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jam_kerja}}');
    }
}
