<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%master_gaji}}`.
 */
class m241010_030246_create_master_gaji_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('master_gaji', [
            'id_gaji' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'nominal_gaji' => $this->decimal(10, 2)->notNull(),
            'visiblity' => $this->smallInteger()->defaultValue(1),
        ]);

        $this->addForeignKey(
            'fk-master_gaji-id_karyawan',
            'master_gaji',
            'id_karyawan',
            'karyawan', // Nama tabel yang berelasi
            'id_karyawan', // Kolom yang berelasi
            'CASCADE', // Aksi saat dihapus
            'CASCADE' // Aksi saat diupdate
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-master_gaji-id_karyawan', 'master_gaji');
        $this->dropTable('master_gaji');
    }
}
