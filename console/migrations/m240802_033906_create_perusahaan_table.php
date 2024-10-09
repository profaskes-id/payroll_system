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
            'provinsi' => $this->string()->notNull(),
            'kabupatan_kota' => $this->string()->notNull(),
            'alamat' => $this->text()->notNull(),
            'direktur' => $this->string()->notNull(),
            'logo' => $this->string()->null(),
            'bidang_perusahaan' => $this->text()->null(),
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
