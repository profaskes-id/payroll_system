<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%periode_gaji}}`.
 */
class m241030_064239_create_periode_gaji_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%periode_gaji}}', [
            'bulan' => $this->integer()->notNull(),
            'tahun' => $this->integer()->notNull(),
            'tanggal_awal' => $this->date()->notNull(),
            'tanggal_akhir' => $this->date()->notNull(),
            'terima' => $this->date()->null(),
            'PRIMARY KEY (bulan, tahun)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%periode_gaji}}');
    }
}
