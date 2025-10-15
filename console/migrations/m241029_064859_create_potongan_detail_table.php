<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%potongan_detail}}`.
 */
class m241029_064859_create_potongan_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%potongan_detail}}', [
            'id_potongan_detail' => $this->primaryKey(),
            'id_potongan' => $this->integer()->notNull(),
            'id_karyawan' => $this->integer()->notNull(),
            'jumlah' => $this->decimal(10, 2)->notNull(), // untuk tipe jumlah yang berupa angka desimal
            'status' => $this->smallInteger()->defaultValue(0),

        ]);

        // Membuat indeks dan menambahkan foreign key untuk id_potongan ke tabel potongan
        $this->createIndex(
            'idx-potongan_detail-id_potongan',
            '{{%potongan_detail}}',
            'id_potongan'
        );

        $this->addForeignKey(
            'fk-potongan_detail-id_potongan',
            '{{%potongan_detail}}',
            'id_potongan',
            '{{%potongan}}',
            'id_potongan',
            'CASCADE'
        );
        // Membuat indeks untuk id_karyawan (asumsi id_karyawan berelasi ke tabel lain, contoh: tabel karyawan)
        $this->createIndex(
            'idx-potongan_detail-id_karyawan',
            '{{%potongan_detail}}',
            'id_karyawan'
        );


        $this->addForeignKey(
            'fk-potongan_detail-id_karyawan',
            '{{%potongan_detail}}',
            'id_karyawan',
            '{{%karyawan}}',
            'id_karyawan',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%potongan_detail}}');
    }
}
