<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pendapatan_potongan_lainnya}}`.
 */
class m260126_132103_create_pendapatan_potongan_lainnya_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pendapatan_potongan_lainnya}}', [
            'id_ppl' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'id_periode_gaji' => $this->integer()->notNull(),
            'keterangan' => $this->text()->defaultValue(null),
            'is_pendapatan' => $this->tinyInteger(1)->defaultValue(0),
            'is_potongan' => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        // FK ke tabel karyawan
        $this->addForeignKey(
            'fk_ppl_karyawan',
            '{{%pendapatan_potongan_lainnya}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'RESTRICT'
        );

        // FK ke tabel periode_gaji
        $this->addForeignKey(
            'fk_ppl_periode_gaji',
            '{{%pendapatan_potongan_lainnya}}',
            'id_periode_gaji',
            '{{%periode_gaji}}',
            'id_periode_gaji',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_ppl_karyawan', '{{%pendapatan_potongan_lainnya}}');
        $this->dropForeignKey('fk_ppl_periode_gaji', '{{%pendapatan_potongan_lainnya}}');

        $this->dropTable('{{%pendapatan_potongan_lainnya}}');
    }
}
