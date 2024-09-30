<?php

use yii\db\Migration;

/**
 * Class m240930_063746_add_forgeinkey_in_up_lokasi_karyawan_table
 */
class m240930_063746_add_forgeinkey_in_up_lokasi_karyawan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('atasan_karyawan', 'id_master_lokasi', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-bagian-id_master_lokasi', // Nama constraint
            '{{%atasan_karyawan}}',
            'id_master_lokasi',
            '{{%master_lokasi}}',
            'id_master_lokasi',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bagian-id_master_lokasi', 'bagian');

        // Menghapus kolom id_master_lokasi dari tabel bagian
        $this->dropColumn('bagian', 'id_master_lokasi');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240930_063746_add_forgeinkey_in_up_lokasi_karyawan_table cannot be reverted.\n";

        return false;
    }
    */
}
