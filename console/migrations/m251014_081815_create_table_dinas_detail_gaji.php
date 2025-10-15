<?php

use yii\db\Migration;

class m251014_081815_create_table_dinas_detail_gaji extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%dinas_detail_gaji}}', [
            'id_dinas_detail' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'bulan' => $this->integer()->null(),
            'tahun' => $this->integer()->null(),
            'nama' => $this->string(255)->notNull(),
            'tanggal' => $this->date()->notNull(),
            'keterangan' => $this->text(),
            'biaya' => $this->decimal(15, 2)->notNull()->defaultValue(0.00),
        ]);

        $this->createIndex(
            'idx-dinas_detail-id_karyawan',
            '{{%dinas_detail_gaji}}',
            'id_karyawan'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%dinas_detail_gaji}}');
    }
}
