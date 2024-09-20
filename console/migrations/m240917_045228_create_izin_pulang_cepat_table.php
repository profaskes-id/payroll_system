<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%izin_pulang_cepat}}`.
 */
class m240917_045228_create_izin_pulang_cepat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%izin_pulang_cepat}}', [
            'id_izin_pulang_cepat' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'alasan' => $this->text()->notNull(),
            'tanggal' => $this->date()->notNull()->defaultValue(date('Y-m-d H:i:s')),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'disetujui_pada' => $this->dateTime(),
            'disetujui_oleh' => $this->integer(),
            'catatan_admin' => $this->text(),
        ]);
        $this->addForeignKey(
            'fk_izin_pulang_cepat_id_karyawan',
            '{{%izin_pulang_cepat}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE',
            'CASCADE'
        );
    }
    // Add foreign key for table `karyawan` (id_atasan)


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_izin_pulang_cepat_id_karyawan',
            'pengajuan_pulang_cepat'
        );
        $this->dropTable('{{%izin_pulang_cepat}}');
    }
}
