<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%potongan_alfa_and_wfh_penggajian}}`.
 */
class m251017_030338_create_potongan_alfa_and_wfh_penggajian_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%potongan_alfa_and_wfh_penggajian}}', [
            'id_alfa_wfh' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'nama' => $this->string()->notNull(),
            'bulan' => $this->integer()->notNull(),
            'tahun' => $this->integer()->notNull(),
            'jumlah_alfa' => $this->integer()->notNull()->defaultValue(0),
            'total_potongan_alfa' => $this->float()->notNull()->defaultValue(0),
            'jumlah_wfh' => $this->integer()->notNull()->defaultValue(0),
            'persen_potongan_wfh' => $this->float()->notNull()->defaultValue(0),
            'total_potongan_wfh' => $this->float()->notNull()->defaultValue(0),
            'gaji_perhari' => $this->float()->notNull()->defaultValue(0),
        ]);
    }


    public function safeDown()
    {
        $this->dropTable('{{%potongan_alfa_and_wfh_penggajian}}');
    }
}
