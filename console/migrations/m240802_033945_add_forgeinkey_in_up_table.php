<?php

use yii\db\Migration;

/**
 * Class m240802_033945_add_forgeinkey_in_up_table
 */
class m240802_033945_add_forgeinkey_in_up_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bagian', 'id_perusahaan', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-bagian-id_perusahaan', // Nama constraint
            '{{%bagian}}',
            'id_perusahaan',
            '{{%perusahaan}}',
            'id_perusahaan',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bagian-id_perusahaan', 'bagian');

        // Menghapus kolom id_perusahaan dari tabel bagian
        $this->dropColumn('bagian', 'id_perusahaan');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240802_033945_add_forgeinkey_in_up_table cannot be reverted.\n";

        return false;
    }
    */
}
