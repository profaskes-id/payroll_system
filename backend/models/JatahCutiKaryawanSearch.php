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
        $query = (new Query())
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_jenis_kelamin',
                'jatah_cuti_karyawan.tahun',
                'jatah_cuti_karyawan.id_jatah_cuti',
                'jatah_cuti_karyawan.jatah_hari_cuti',
                'jatah_cuti_karyawan.id_master_cuti',
            ])
            ->from('karyawan')
            ->leftJoin(
                'jatah_cuti_karyawan',
                'jatah_cuti_karyawan.id_karyawan = karyawan.id_karyawan' .
                    ($tahun !== null ? " AND jatah_cuti_karyawan.tahun = $tahun" : "") .
                    ($id_master_cuti !== null ? " AND jatah_cuti_karyawan.id_master_cuti = $id_master_cuti" : "")
            )
            ->where(['karyawan.is_aktif' => 1])
            ->orderBy(['karyawan.nama' => SORT_ASC]);

        // Filter karyawan spesifik
        if ($id_karyawan) {
            $query->andWhere(['karyawan.id_karyawan' => $id_karyawan]);
        }

        // Jika cuti khusus perempuan
        if ($id_master_cuti == 2) {
            $query->andWhere(['karyawan.kode_jenis_kelamin' => 'P']);
        }

        $query->groupBy([
            'karyawan.id_karyawan',
            'karyawan.nama',
            'karyawan.kode_jenis_kelamin',
            'jatah_cuti_karyawan.tahun',
            'jatah_cuti_karyawan.id_jatah_cuti',
            'jatah_cuti_karyawan.jatah_hari_cuti',
            'jatah_cuti_karyawan.id_master_cuti',
        ]);

        return new \yii\data\ArrayDataProvider([
            'allModels' => $query->all(),
            'pagination' => ['pageSize' => 20],
        ]);
    }
}
