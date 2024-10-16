<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DataPekerjaan;

/**
 * DataPekerjaanSearch represents the model behind the search form of `backend\models\DataPekerjaan`.
 */
class DataPekerjaanSearch extends DataPekerjaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_data_pekerjaan', 'id_karyawan', 'id_bagian'], 'integer'],
            [['dari', 'sampai', 'status', 'jabatan'], 'safe'],
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
        $query = DataPekerjaan::find()
            ->select([
                'data_pekerjaan.id_data_pekerjaan',
                'data_pekerjaan.id_karyawan',
                'data_pekerjaan.id_bagian',
                'data_pekerjaan.dari',
                'data_pekerjaan.sampai',
                'data_pekerjaan.status',
                'data_pekerjaan.jabatan',
                'data_pekerjaan.is_aktif',
                'data_pekerjaan.surat_lamaran_pekerjaan',
                'cetak.id_cetak',
                'cetak.surat_upload',
                'bagian.id_bagian',
                'bagian.nama_bagian',
                'karyawan.id_karyawan',
                'mk.nama_kode as status_pekerjaan',

            ])
            ->asArray()
            ->where(['data_pekerjaan.id_karyawan' => $this->id_karyawan])
            ->leftJoin('{{%cetak}} cetak', 'data_pekerjaan.id_data_pekerjaan = cetak.id_data_pekerjaan and data_pekerjaan.id_karyawan = cetak.id_karyawan')
            ->leftJoin('{{%karyawan}} karyawan', 'data_pekerjaan.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('{{%bagian}} bagian', 'data_pekerjaan.id_bagian = bagian.id_bagian')
            ->leftJoin('{{%master_kode}} mk', 'mk.nama_group = "status-pekerjaan" and data_pekerjaan.status = mk.kode');


        // dd($query->all());

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['is_aktif' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_data_pekerjaan' => $this->id_data_pekerjaan,
            // 'id_karyawan' => $this->id_karyawan,
            'id_bagian' => $this->id_bagian,
            'dari' => $this->dari,
            'sampai' => $this->sampai,
            'is_aktif' => $this->is_aktif,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'jabatan', $this->jabatan]);


        return $dataProvider;
    }
}
