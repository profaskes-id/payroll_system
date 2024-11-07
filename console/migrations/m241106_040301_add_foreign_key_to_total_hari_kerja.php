<?php

use yii\db\Migration;

/**
 * Class m241106_040301_add_foreign_key_to_total_hari_kerja
 */
class m241106_040301_add_foreign_key_to_total_hari_kerja extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('total_hari_kerja', 'id_periode_gaji', $this->integer()->notNull());

        // Menambahkan foreign key pada kolom id_periode_gaji
        $this->addForeignKey(
            'fk-total_hari_kerja-id_periode_gaji',  // Nama constraint foreign key
            'total_hari_kerja',                     // Tabel asal
            'id_periode_gaji',                      // Kolom di tabel asal
            'periode_gaji',                         // Tabel referensi
            'id_periode_gaji',                      // Kolom referensi
            'CASCADE',                              // Action saat dihapus (CASCADE)
            'CASCADE'                               // Action saat diupdate (CASCADE)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-total_hari_kerja-id_periode_gaji', 'total_hari_kerja');

        // Menghapus kolom id_periode_gaji
        $this->dropColumn('total_hari_kerja', 'id_periode_gaji');
    }
}
