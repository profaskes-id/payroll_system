<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "expenses_detail".
 *
 * @property string $id_expense_detail
 * @property string $id_expense_header
 * @property int $id_kategori_expenses
 * @property int $id_subkategori_expenses
 * @property float $jumlah
 * @property string|null $keterangan
 *
 * @property ExpensesHeader $expenseHeader
 * @property KategoriExpenses $kategoriExpenses
 * @property SubkategoriExpenses $subkategoriExpenses
 */
class ExpensesDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_expense_header', 'id_kategori_expenses', 'id_subkategori_expenses'], 'required'],
            [['id_kategori_expenses', 'id_subkategori_expenses',], 'integer'],
            [['jumlah'], 'number'],
            [['keterangan'], 'string'],
            [['id_expense_detail', 'id_expense_header'], 'string', 'max' => 10],
            [['id_expense_detail'], 'unique'],
            [['id_expense_header'], 'exist', 'skipOnError' => true, 'targetClass' => ExpensesHeader::class, 'targetAttribute' => ['id_expense_header' => 'id_expense_header']],
            [['id_kategori_expenses'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriExpenses::class, 'targetAttribute' => ['id_kategori_expenses' => 'id_kategori_expenses']],
            [['id_subkategori_expenses'], 'exist', 'skipOnError' => true, 'targetClass' => SubkategoriExpenses::class, 'targetAttribute' => ['id_subkategori_expenses' => 'id_subkategori_expenses']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_expense_detail' => 'IExpense Detail',
            'id_expense_header' => ' Expense Header',
            'id_kategori_expenses' => ' Kategori Expenses',
            'id_subkategori_expenses' => ' Subkategori Expenses',
            'jumlah' => 'Jumlah',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * Gets query for [[ExpenseHeader]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseHeader()
    {
        return $this->hasOne(ExpensesHeader::class, ['id_expense_header' => 'id_expense_header']);
    }

    /**
     * Gets query for [[KategoriExpenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriExpenses()
    {
        return $this->hasOne(KategoriExpenses::class, ['id_kategori_expenses' => 'id_kategori_expenses']);
    }

    /**
     * Gets query for [[SubkategoriExpenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubkategoriExpenses()
    {
        return $this->hasOne(SubkategoriExpenses::class, ['id_subkategori_expenses' => 'id_subkategori_expenses']);
    }



    static function getHeaderAndDetailData()
    {
        $data = ExpensesDetail::find()
            ->select(['expenses_detail.*', 'expenses_header.id_expense_header', 'expenses_header.tanggal', 'kategori_expenses.nama_kategori', 'subkategori_expenses.nama_subkategori'])
            ->asArray()
            ->leftJoin('kategori_expenses', 'kategori_expenses.id_kategori_expenses = expenses_detail.id_kategori_expenses')
            ->leftJoin('subkategori_expenses', 'subkategori_expenses.id_subkategori_expenses = expenses_detail.id_subkategori_expenses')
            ->leftJoin('expenses_header', 'expenses_header.id_expense_header = expenses_detail.id_expense_header');
        return $data;
    }

    static function getHeaderAndDetailDataWhereHeaderKode($id)
    {
        $data = ExpensesDetail::find()
            ->where(['expenses_detail.id_expense_header' => $id])
            ->select(['expenses_detail.*' , 'expenses_header.tanggal', 'kategori_expenses.nama_kategori', 'subkategori_expenses.nama_subkategori'])
            ->leftJoin('expenses_header', 'expenses_header.id_expense_header = expenses_detail.id_expense_header')
            ->leftJoin('kategori_expenses', 'kategori_expenses.id_kategori_expenses = expenses_detail.id_kategori_expenses')
            ->leftJoin('subkategori_expenses', 'subkategori_expenses.id_subkategori_expenses = expenses_detail.id_subkategori_expenses')
            ->asArray()
            ->orderBy(['expenses_detail.id_expense_detail' => SORT_DESC])
            ->all();
        return $data;
    }
}
