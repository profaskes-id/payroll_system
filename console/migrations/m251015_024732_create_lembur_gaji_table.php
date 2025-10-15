<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lembur_gaji}}`.
 */
class m251015_024732_create_lembur_gaji_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lembur_gaji}}', [
            'id_lembur_gaji' => $this->primaryKey(),
            'nama' => $this->string(255)->notNull(),
            'bulan' => $this->integer()->null(),
            'tahun' => $this->integer()->null(),
            'id_karyawan' => $this->integer()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'hitungan_jam' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%lembur_gaji}}');
    }
}
