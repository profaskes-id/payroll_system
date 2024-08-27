<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pengumuman".
 *
 * @property int $id_pengumuman
 * @property string $judul
 * @property string $deskripsi
 * @property string $dibuat_pada
 * @property string|null $update_pada
 * @property int|null $dibuat_oleh
 */
class Pengumuman extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengumuman';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['judul', 'deskripsi', 'dibuat_pada'], 'required'],
            [['deskripsi'], 'string'],
            [['dibuat_pada', 'update_pada'], 'safe'],
            [['dibuat_oleh'], 'integer'],
            [['judul'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pengumuman' => 'Id Pengumuman',
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'dibuat_pada' => 'Dibuat Pada',
            'update_pada' => 'Update Pada',
            'dibuat_oleh' => 'Dibuat Oleh',
        ];
    }
}
