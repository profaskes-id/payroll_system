<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%perusahaan}}`.
 */
class m240802_033906_create_perusahaan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%perusahaan}}', [
            'id_perusahaan' => $this->primaryKey(),
            'nama_perusahaan' => $this->string()->notNull(),
            'status_perusahaan' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%perusahaan}}');
    }
}
