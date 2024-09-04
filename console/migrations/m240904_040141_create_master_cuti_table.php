<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%master_cuti}}`.
 */
class m240904_040141_create_master_cuti_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%master_cuti}}', [
            'id_master_cuti' => $this->primaryKey(),
            'jenis_cuti' => $this->string()->notNull(),
            'deskripsi_singkat' => $this->text(),
            'total_hari_pertahun' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%master_cuti}}');
    }
}
