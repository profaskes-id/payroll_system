<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_absensi}}`.
 */
class m250717_022916_create_pengajuan_absensi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengajuan_absensi}}', [
            'id' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'tanggal_absen' => $this->date()->notNull(),
            'jam_masuk' => $this->time()->defaultValue(null),
            'jam_keluar' => $this->time()->defaultValue(null),
            'alasan_pengajuan' => $this->text()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0), // 0 = Pending
            'tanggal_pengajuan' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'id_approver' => $this->integer()->null(),
            'tanggal_disetujui' => $this->timestamp()->null(),
            'catatan_approver' => $this->text()->null(),
        ]);

        // Optional: Tambahkan foreign key jika punya relasi ke tabel karyawan dan user (approver)
        $this->addForeignKey(
            'fk-pengajuan_absensi-id_karyawan',
            '{{%pengajuan_absensi}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-pengajuan_absensi-id_approver',
            '{{%pengajuan_absensi}}',
            'id_approver',
            '{{%user}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pengajuan_absensi}}');
    }
}
