<?php

use yii\db\Migration;

/**
 * Class m241101_080258_add_foreign_key_gaji_tunjangan
 */
class m241101_080258_add_foreign_key_gaji_tunjangan extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('gaji_tunjangan', 'id_transaksi_gaji', $this->integer());

        // Kemudian tambahkan foreign key
        $this->addForeignKey(
            'fk_gaji_tunjangan_transaksi_gaji', // nama foreign key
            'gaji_tunjangan', // tabel yang akan ditambahkan foreign key
            'id_transaksi_gaji', // kolom yang akan menjadi foreign key
            'transaksi_gaji', // tabel referensi
            'id_transaksi_gaji', // kolom referensi di tabel transaksi_gaji
            'CASCADE', // aksi ketika data di tabel referensi di-update
            'CASCADE' // aksi ketika data di tabel referensi di-delete
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_gaji_tunjangan_transaksi_gaji', 'gaji_tunjangan');

        $this->dropColumn('gaji_tunjangan', 'id_transaksi_gaji');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241101_080258_add_foreign_key_gaji_tunjangan cannot be reverted.\n";

        return false;
    }
    */
}
