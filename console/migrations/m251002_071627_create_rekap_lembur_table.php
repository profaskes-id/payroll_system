<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rekap_lembur}}`.
 */
class m251002_071627_create_rekap_lembur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rekap_lembur}}', [
            'id_rekap_lembur' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'jam_total' => $this->integer()->notNull(),
        ]);

        // creates index for column `id_karyawan`
        $this->createIndex(
            '{{%idx-rekap_lembur-id_karyawan}}',
            '{{%rekap_lembur}}',
            'id_karyawan'
        );

        // add foreign key for table `{{%karyawan}}`
        $this->addForeignKey(
            '{{%fk-rekap_lembur-id_karyawan}}',
            '{{%rekap_lembur}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drops foreign key
        $this->dropForeignKey(
            '{{%fk-rekap_lembur-id_karyawan}}',
            '{{%rekap_lembur}}'
        );

        // drops index
        $this->dropIndex(
            '{{%idx-rekap_lembur-id_karyawan}}',
            '{{%rekap_lembur}}'
        );

        // drops table
        $this->dropTable('{{%rekap_lembur}}');
    }
}
