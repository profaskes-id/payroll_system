<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "kategori_expenses".
 *
 * @property int $id_kategori_expenses
 * @property string $nama_kategori
 * @property string|null $deskripsi
 * @property string|null $create_at
 * @property int $create_by
 * @property string|null $update_at
 * @property int $update_by
 *
 * @property User $createBy
 * @property ExpensesDetail[] $expensesDetails
 * @property SubkategoriExpenses[] $subkategoriExpenses
 * @property User $updateBy
 */
class KategoriExpenses extends \yii\db\ActiveRecord
{


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // Jika ini adalah insert baru
                $this->create_at = date('Y-m-d H:i:s');
                $this->create_by = Yii::$app->user->id;
                $this->update_by = Yii::$app->user->id;
            } else {
                // Jika ini adalah update
                $this->update_at = date('Y-m-d H:i:s');
                $this->update_by = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori_expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_kategori'], 'required'],
            [['deskripsi'], 'string'],
            [['create_at', 'update_at'], 'safe'],
            [['create_by', 'update_by'], 'integer'],
            [['nama_kategori'], 'string', 'max' => 255],
            [['create_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['create_by' => 'id']],
            [['update_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_kategori_expenses' => 'Id Kategori Expenses',
            'nama_kategori' => 'Nama Kategori',
            'deskripsi' => 'Deskripsi',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    /**
     * Gets query for [[CreateBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(User::class, ['id' => 'create_by']);
    }

    /**
     * Gets query for [[ExpensesDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesDetails()
    {
        return $this->hasMany(ExpensesDetail::class, ['id_kategori_expenses' => 'id_kategori_expenses']);
    }

    /**
     * Gets query for [[SubkategoriExpenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubkategoriExpenses()
    {
        return $this->hasMany(SubkategoriExpenses::class, ['id_kategori_expenses' => 'id_kategori_expenses']);
    }

    /**
     * Gets query for [[UpdateBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(User::class, ['id' => 'update_by']);
    }
}
