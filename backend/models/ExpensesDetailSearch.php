<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ExpensesDetail;
use yii\db\Expression;

/**
 * ExpensesDetailSearch represents the model behind the search form of `backend\models\ExpensesDetail`.
 */
class ExpensesDetailSearch extends ExpensesDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kategori_expenses', 'id_subkategori_expenses',], 'integer'],
            [['jumlah'], 'number'],
            [['keterangan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $tgl_mulai, $tgl_selesai)
    {

        $getExpensesData = new  ExpensesDetail();
        $query = $getExpensesData->getHeaderAndDetailData();
        // $query = ExpensesHeader::find()->where(['>=', 'tanggal', $tgl_mulai])->andWhere(['<=', 'tanggal', $tgl_selesai]);
        $query->leftJoin('expenses_header as eh', 'eh.id_expense_header = expenses_detail.id_expense_header')->where(['>=', 'eh.tanggal', $tgl_mulai])->andWhere(['<=', 'eh.tanggal', $tgl_selesai]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_expense_detail' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 0
            ]

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_expense_detail' => $this->id_expense_detail,
            'exid_expense_header' => $this->id_expense_header,
            'expenses_detail.id_kategori_expenses' => $this->id_kategori_expenses,
            'expenses_detail.id_subkategori_expenses' => $this->id_subkategori_expenses,
            // 'jumlah' => $this->jumlah,
        ]);


        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);
        return $dataProvider;
    }
}
