<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pembayaran_kasbon}}`.
 */
class m251028_025341_create_pembayaran_kasbon_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pembayaran_kasbon}}', [
            'id_pembayaran_kasbon' => $this->primaryKey(),
            'id_karyawan' => $this->integer()->notNull(),
            'id_kasbon' => $this->integer()->notNull(),
            'bulan' => $this->integer()->notNull(),
            'tahun' => $this->integer()->notNull(),
            'jumlah_kasbon' => $this->decimal(15, 2)->defaultValue(0),
            'jumlah_potong' => $this->decimal(15, 2)->defaultValue(0),
            'tanggal_potong' => $this->date()->notNull(),
            'angsuran' => $this->decimal(15, 2)->defaultValue(0),
            'status_potongan' => $this->smallInteger()->defaultValue(0)->comment('0 = belum lunas, 1 = lunas'),
            'sisa_kasbon' => $this->decimal(15, 2)->defaultValue(0),
            'created_at' => $this->integer()->null(),
            'deskripsi' => $this->string(100)->null(),
        ]);

        // 🔗 Relasi ke tabel karyawan
        $this->addForeignKey(
            'fk_pembayaran_kasbon_karyawan',
            '{{%pembayaran_kasbon}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );

        // 🔗 Relasi ke tabel pengajuan_kasbon
        $this->addForeignKey(
            'fk_pembayaran_kasbon_kasbon',
            '{{%pembayaran_kasbon}}',
            'id_kasbon',
            '{{%pengajuan_kasbon}}',
            'id_pengajuan_kasbon',
            'CASCADE'
        );
    }



    public function safeDown()
    {
        $this->dropForeignKey('fk_pembayaran_kasbon_kasbon', '{{%pembayaran_kasbon}}');
        $this->dropForeignKey('fk_pembayaran_kasbon_karyawan', '{{%pembayaran_kasbon}}');
        $this->dropTable('{{%pembayaran_kasbon}}');
    }
}
