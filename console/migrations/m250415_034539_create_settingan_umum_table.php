<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%settingan_umum}}`.
 */
class m250415_034539_create_settingan_umum_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settingan_umum}}', [
            'id_settingan_umum' => $this->primaryKey(),
            'kode_setting' => $this->string()->notNull(),
            'nama_setting' => $this->string()->notNull(),
            'nilai_setting' => $this->integer(),
            'ket' => $this->text()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settingan_umum}}');
    }
}
