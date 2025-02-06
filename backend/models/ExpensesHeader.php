<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use Yii;

/**
 * This is the model class for table "expenses_header".
 *
 * @property string $id_expense_header
 * @property string $tanggal
 * @property string|null $create_at
 * @property int $create_by
 * @property string|null $update_at
 * @property int $update_by
 *
 * @property User $createBy
 * @property ExpensesDetail[] $expensesDetails
 * @property User $updateBy
 */
class ExpensesHeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

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

    public static function tableName()
    {
        return 'expenses_header';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_expense_header',], 'required'],
            [['tanggal', 'create_at', 'update_at'], 'safe'],
            [['create_by', 'update_by'], 'integer'],
            [['id_expense_header'], 'string', 'max' => 10],
            [['id_expense_header'], 'unique'],
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
            'id_expense_header' => ' Expense Header',
            'tanggal' => 'Tanggal',
            'create_at' => 'Dibuat Pada',
            'create_by' => 'Dibuat Oleh',
            'update_at' => 'Diperbarui Pada',
            'update_by' => 'Diperbarui Oleh',
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
        return $this->hasMany(ExpensesDetail::class, ['id_expense_header' => 'id_expense_header']);
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


    public function generateAutoCode()
    {
        $ym = date('ym');
        $_left = "TRN" . $ym;
        $_first = "01";
        $_len = strlen($_left);
        $noTransaksi = $_left . $_first;
        $last_kode = $this->find()
            ->where(['left(id_expense_header,' . $_len . ')' => $_left])
            ->orderBy(['id_expense_header' => SORT_DESC])
            ->one();


        if ($last_kode != null) {
            $_no = substr($last_kode['id_expense_header'], $_len);
            $_no++;
            $_no = substr("00", strlen($_no)) . $_no;
            $noTransaksi = $_left . $_no;
        }

        if ($this->isNewRecord) {
            return $noTransaksi;
        }
    }
}
