<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_tugas_luar}}`.
 */
class m250729_023544_create_pengajuan_tugas_luar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengajuan_tugas_luar}}', [
            'id_tugas_luar' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'catatan_approver' => $this->text()->null(),
            'status_pengajuan' => $this->smallInteger()->notNull()->defaultValue(0)->comment('0=pending, 1=disetujui, 2=ditolak'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
              'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ]);

        // Buat tabel untuk detail lokasi tugas luar (seperti todo list)
        $this->createTable('{{%detail_tugas_luar}}', [
            'id_detail' => $this->primaryKey(),
            'id_tugas_luar' => $this->integer()->notNull(),
            'keterangan' => $this->string(100)->notNull()->comment('Contoh: Ke Jakarta untuk xxxxxx, Ke Bandung untuk xxxxx'),
            'jam_diajukan' => $this->time()->notNull(),
            'jam_check_in' => $this->time()->null(),
            'longitude' => $this->string(50)->null(),
            'latitude' => $this->string(50)->null(),
            'status_check' => $this->boolean()->notNull()->defaultValue(0)->comment('0=belum, 1=sudah'),
            'urutan' => $this->integer()->notNull(),
            'bukti_foto' => $this->string(100)->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
          
        ]);

        // Tambahkan foreign key
        $this->addForeignKey(
            'fk-pengajuan_tugas_luar-karyawan',
            '{{%pengajuan_tugas_luar}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-detail_tugas_luar-tugas_luar',
            '{{%detail_tugas_luar}}',
            'id_tugas_luar',
            '{{%pengajuan_tugas_luar}}',
            'id_tugas_luar',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-detail_tugas_luar-tugas_luar', '{{%detail_tugas_luar}}');
        $this->dropForeignKey('fk-pengajuan_tugas_luar-karyawan', '{{%pengajuan_tugas_luar}}');
        $this->dropTable('{{%detail_tugas_luar}}');
        $this->dropTable('{{%pengajuan_tugas_luar}}');
    }
}
