<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_lembur}}`.
 */
class m240821_080846_create_pengajuan_lembur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pengajuan_lembur', [
            'id_pengajuan_lembur' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'pekerjaan' => $this->text()->notNull(), // Menggunakan text untuk menyimpan array dalam format JSON
            'status' => $this->integer()->notNull(),
            'jam_mulai' => $this->time()->notNull(),
            'jam_selesai' => $this->time()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'disetujui_pada' => $this->dateTime()->null(),
            'disetujui_oleh' => $this->integer()->null(),
        ]);


        // Create index for `id_karyawan`
        $this->createIndex(
            '{{%idx-pengajuan_lembur-id_karyawan}}',
            '{{%pengajuan_lembur}}',
            'id_karyawan'
        );

        // Add foreign key for `id_karyawan`
        $this->addForeignKey(
            '{{%fk-pengajuan_lembur-id_karyawan}}',
            '{{%pengajuan_lembur}}',
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
            '{{%fk-pengajuan_lembur-id_karyawan}}',
            '{{%pengajuan_lembur}}'
        );

        // Drop index for `id_karyawan`
        $this->dropIndex(
            '{{%idx-pengajuan_lembur-id_karyawan}}',
            '{{%pengajuan_lembur}}'
        );
        $this->dropTable('{{%pengajuan_lembur}}');
    }
}
