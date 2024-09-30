<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%master_lokasi}}`.
 */
class m240930_044701_create_master_lokasi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%master_lokasi}}', [
            'id_master_lokasi' => $this->primaryKey(),
            'label' => $this->string()->notNull(),
            'alamat' => $this->string()->notNull(),
            'longtitude' => $this->double()->notNull(),
            'latitude' => $this->double()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%master_lokasi}}');
    }
}
