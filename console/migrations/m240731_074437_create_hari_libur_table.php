<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hari_libur}}`.
 */
class m240731_074437_create_hari_libur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hari_libur}}', [
            'id_hari_libur' => $this->primaryKey(), // Primary key with auto-increment
            'tanggal' => $this->date()->notNull(), // Date column
            'nama_hari_libur' => $this->string()->notNull(), // Name of the holiday
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hari_libur}}');
    }
}
