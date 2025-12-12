<?php

use yii\db\Migration;

class m251122_113326_detail_dinas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%detail_dinas}}', [
            'id_detail_dinas' => $this->primaryKey(),
            'id_pengajuan_dinas' => $this->integer()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'keterangan' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(0),
        ]);

        // Add foreign key for `id_pengajuan_dinas`
        $this->addForeignKey(
            '{{%fk-detail_dinas-id_pengajuan_dinas}}',
            '{{%detail_dinas}}',
            'id_pengajuan_dinas',
            '{{%pengajuan_dinas}}',
            'id_pengajuan_dinas',
            'CASCADE'
        );
    }



    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%detail_dinas}}');
    }
}
