<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rekap_cuti}}`.
 */
class m240904_040420_create_rekap_cuti_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rekap_cuti}}', [
            'id_rekap_cuti' => $this->primaryKey(),
            'id_master_cuti' => $this->integer()->notNull(),
            'id_karyawan' => $this->integer()->notNull(),
            'total_hari_terpakai' => $this->integer()->notNull(),
        ]);

        // Add foreign key for table `karyawan`
        $this->addForeignKey(
            'fk-rekap_cuti-id_karyawan',
            '{{%rekap_cuti}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-rekap_cuti-id_master_cuti',     // Foreign key name
            '{{%rekap_cuti}}',                  // Table to add foreign key to
            'id_master_cuti',                   // Column in the table
            '{{%master_cuti}}',                  // Referenced table
            'id_master_cuti',                   // Referenced column
            'CASCADE',                          // On delete
            'CASCADE'                           // On update
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key for table `karyawan`
        $this->dropForeignKey(
            'fk-rekap_cuti-id_karyawan',
            '{{%rekap_cuti}}'
        );


        // Drop table `rekap_cuti`
        $this->dropTable('{{%rekap_cuti}}');
    }
}
