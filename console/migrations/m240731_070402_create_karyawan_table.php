<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%karyawan}}`.
 */
class m240731_070402_create_karyawan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%karyawan}}', [
            'id_karyawan' => $this->primaryKey(), // Primary key with auto-increment
            'kode_karyawan' => $this->string()->notNull(), // Adjust the length if needed
            'nama' => $this->string()->notNull(),
            'nomer_identitas' => $this->string()->notNull(),
            'jenis_identitas' => $this->string()->notNull(),
            'tanggal_lahir' => $this->date()->notNull(),
            'kode_negara' => $this->string()->notNull(), // Adjust length if needed
            'kode_provinsi' => $this->string()->notNull(),
            'kode_kabupaten_kota' => $this->string()->notNull(),
            'kode_kecamatan' => $this->string()->notNull(),
            'alamat' => $this->text()->notNull(),
            'kode_jenis_kelamin' => $this->integer()->notNull(),
            'email' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%karyawan}}');
    }
}
