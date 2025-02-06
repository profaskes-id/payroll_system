<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id_message
 * @property int $sender
 * @property string $judul
 * @property string $deskripsi
 * @property string $create_at
 * @property int $create_by
 * @property string|null $nama_transaksi
 * @property int|null $id_transaksi
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender', 'judul', 'deskripsi', 'create_by'], 'required'],
            [['sender', 'create_by', 'id_transaksi'], 'integer'],
            [['deskripsi'], 'string'],
            [['create_at'], 'safe'],
            [['judul', 'nama_transaksi'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_message' => 'Id Message',
            'sender' => 'Sender',
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'nama_transaksi' => 'Nama Transaksi',
            'id_transaksi' => 'Id Transaksi',
        ];
    }
}
