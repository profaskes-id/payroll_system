<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_cuti}}`.
 */
class m240819_021120_create_pengajuan_cuti_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengajuan_cuti}}', [
            'id_pengajuan_cuti' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'tanggal_pengajuan' => $this->date()->notNull(),
            'jenis_cuti' => $this->integer()->notNull(),
            'alasan_cuti' => $this->text(),
            'status' => $this->integer()->defaultValue(0),
            'catatan_admin' => $this->text(),
            'ditanggapi_pada' => $this->dateTime()->null(),
            'ditanggapi_oleh' => $this->integer()->null(),
        ]);

        // Create index for `id_karyawan`
        $this->createIndex(
            '{{%idx-pengajuan_cuti-id_karyawan}}',
            '{{%pengajuan_cuti}}',
            'id_karyawan'
        );

        // Add foreign key for `id_karyawan`
        $this->addForeignKey(
            '{{%fk-pengajuan_cuti-id_karyawan}}',
            '{{%pengajuan_cuti}}',
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

        // Drop foreign key for `id_karyawan`
        $this->dropForeignKey(
            '{{%fk-pengajuan_cuti-id_karyawan}}',
            '{{%pengajuan_cuti}}'
        );

        // Drop index for `id_karyawan`
        $this->dropIndex(
            '{{%idx-pengajuan_cuti-id_karyawan}}',
            '{{%pengajuan_cuti}}'
        );
        $this->dropTable('{{%pengajuan_cuti}}');
    }
}
