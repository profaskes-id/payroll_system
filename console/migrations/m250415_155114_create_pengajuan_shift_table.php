<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_shift}}`.
 */
class m250415_155114_create_pengajuan_shift_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengajuan_shift}}', [
            'id_pengajuan_shift' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'id_shift_kerja' => $this->integer()->notNull(),
            'diajukan_pada' => $this->date()->notNull(),
            'status' => $this->smallInteger()->defaultValue(0),
            'ditanggapi_oleh' => $this->integer()->null(),
            'ditanggapi_pada' => $this->date()->null(),
            'catatan_admin' => $this->text()->null(),
        ]);

        // Foreign key to karyawan
        $this->addForeignKey(
            'fk-pengajuan_shift-id_karyawan',
            '{{%pengajuan_shift}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        // Foreign key to shift_kerja
        $this->addForeignKey(
            'fk-pengajuan_shift-id_shift_kerja',
            '{{%pengajuan_shift}}',
            'id_shift_kerja',
            '{{%shift_kerja}}',
            'id_shift_kerja',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-pengajuan_shift-id_karyawan',
            '{{%pengajuan_shift}}'
        );

        $this->dropForeignKey(
            'fk-pengajuan_shift-id_shift_kerja',
            '{{%pengajuan_shift}}'
        );
        $this->dropTable('{{%pengajuan_shift}}');
    }
}
