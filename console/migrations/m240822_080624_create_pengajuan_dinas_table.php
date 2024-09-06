<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pengajuan_dinas}}`.
 */
class m240822_080624_create_pengajuan_dinas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pengajuan_dinas}}', [
            'id_pengajuan_dinas' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'keterangan_perjalanan' => $this->text()->notNull(),
            'tanggal_mulai' => $this->date()->notNull(),
            'tanggal_selesai' => $this->date()->notNull(),
            'estimasi_biaya' => $this->decimal(10, 2)->notNull(),
            'biaya_yang_disetujui' => $this->decimal(10, 2),
            'status' => $this->integer()->defaultValue(0),
            'disetujui_pada' => $this->dateTime(),
            'disetujui_oleh' => $this->integer(),
        ]);

        // Add foreign key constraints if necessary
        // For example, if you have a `karyawan` table and want to add a foreign key constraint
        $this->addForeignKey(
            'fk-pengajuan-dinas-id-karyawan',
            '{{%pengajuan_dinas}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        // Optionally, you can also add an index for performance optimization
        $this->createIndex(
            'idx-pengajuan-dinas-id-karyawan',
            '{{%pengajuan_dinas}}',
            'id_karyawan'
        );

        $this->createIndex(
            'idx-pengajuan-dinas-disetujui-oleh',
            '{{%pengajuan_dinas}}',
            'disetujui_oleh'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pengajuan_dinas}}');
    }
}
