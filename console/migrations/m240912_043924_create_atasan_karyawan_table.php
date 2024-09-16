<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%atasan_karyawan}}`.
 */
class m240912_043924_create_atasan_karyawan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%atasan_karyawan}}', [
            'id_atasan_karyawan' => $this->primaryKey(),
            'id_atasan' => $this->integer()->notNull(),
            'id_karyawan' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'di_setting_oleh' => $this->integer()->null(),
            'di_setting_pada' => $this->timestamp()->null(),
        ]);


        // Add foreign key for table `karyawan` (id_atasan)
        $this->addForeignKey(
            'fk-atasan-karyawan-id-atasan',
            '{{%atasan_karyawan}}',
            'id_atasan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-atasan-karyawan-id-karyawan',
            '{{%atasan_karyawan}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-atasan-karyawan-id-atasan', '{{%atasan_karyawan}}');
        $this->dropForeignKey('fk-atasan-karyawan-id-karyawan', '{{%atasan_karyawan}}');
        $this->dropTable('{{%atasan_karyawan}}');
    }
}
