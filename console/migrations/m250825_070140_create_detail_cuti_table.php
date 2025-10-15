<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%detail_cuti}}`.
 */
class m250825_070140_create_detail_cuti_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%detail_cuti}}', [
            'id_detail_cuti' => $this->primaryKey(),
            'id_pengajuan_cuti' => $this->integer()->notNull(),
            'tanggal' => $this->date()->notNull(),
            'keterangan' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(0),
        ]);

        // Add foreign key for `id_pengajuan_cuti`
        $this->addForeignKey(
            '{{%fk-detail_cuti-id_pengajuan_cuti}}',
            '{{%detail_cuti}}',
            'id_pengajuan_cuti',
            '{{%pengajuan_cuti}}',
            'id_pengajuan_cuti',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%detail_cuti}}');
    }
}
