<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JatahCutiKaryawan;
use yii\db\Query;

/**
 * JatahCutiKaryawanSearch represents the model behind the search form of `backend\models\JatahCutiKaryawan`.
 */
class JatahCutiKaryawanSearch extends JatahCutiKaryawan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jatah_cuti', 'id_karyawan', 'id_master_cuti', 'jatah_hari_cuti', 'tahun', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */


    public function search($params, $formName = null, $tahun = null, $id_master_cuti = null, $id_karyawan = null)
    {
        // default master cuti jika tidak diisi
        if ($id_master_cuti === null) {
            $id_master_cuti = 1;
        }

        // SUBQUERY: ambil tepat 1 jatah_cuti_karyawan per karyawan
        $subQuery = (new \yii\db\Query())
            ->from('jatah_cuti_karyawan')
            ->where([
                'id_master_cuti' => $id_master_cuti
            ])
            ->andFilterWhere([
                'tahun' => $tahun
            ]);

        $query = (new Query())
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_jenis_kelamin',
                'j.tahun',
                'j.id_jatah_cuti',
                'j.jatah_hari_cuti',
                'j.id_master_cuti',
            ])
            ->from('karyawan')
            ->leftJoin(['j' => $subQuery], 'j.id_karyawan = karyawan.id_karyawan')
            ->where(['karyawan.is_aktif' => 1])
            ->orderBy(['karyawan.nama' => SORT_ASC]);

        // filter khusus karyawan tertentu
        if ($id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $id_karyawan]);
        }

        // Jika cuti khusus perempuan
        if ($id_master_cuti == 2) {
            $query->andWhere(['karyawan.kode_jenis_kelamin' => 'P']);
        }

        return new \yii\data\ArrayDataProvider([
            'allModels' => $query->all(),
            'pagination' => ['pageSize' => 20],
        ]);
    }
}
