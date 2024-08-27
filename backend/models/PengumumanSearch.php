<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pengumuman;

/**
 * PengumumanSearch represents the model behind the search form of `backend\models\Pengumuman`.
 */
class PengumumanSearch extends Pengumuman
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pengumuman', 'dibuat_pada', 'update_pada', 'dibuat_oleh'], 'integer'],
            [['judul', 'deskripsi'], 'safe'],
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
    public function search($params)
    {
        $query = Pengumuman::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_pengumuman' => $this->id_pengumuman,
            'dibuat_pada' => $this->dibuat_pada,
            'update_pada' => $this->update_pada,
            'dibuat_oleh' => $this->dibuat_oleh,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
