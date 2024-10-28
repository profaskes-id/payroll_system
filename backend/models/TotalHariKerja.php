<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "total_hari_kerja".
 *
 * @property int $id_total_hari_kerja
 * @property int $id_jam_kerja
 * @property int $total_hari
 * @property int $bulan
 * @property int $tahun
 * @property string|null $keterangan
 * @property int|null $is_aktif
 *
 * @property JamKerja $jamKerja
 */
class TotalHariKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'total_hari_kerja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jam_kerja', 'total_hari', 'bulan', 'tahun'], 'required'],
            [['id_jam_kerja', 'total_hari', 'bulan', 'tahun', 'is_aktif'], 'integer'],
            [['keterangan'], 'string', 'max' => 255],
            [['id_jam_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => JamKerja::class, 'targetAttribute' => ['id_jam_kerja' => 'id_jam_kerja']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_total_hari_kerja' => ' Total Hari Kerja',
            'id_jam_kerja' => 'Jam Kerja',
            'total_hari' => 'Total Hari',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'keterangan' => 'Keterangan',
            'is_aktif' => 'Is Aktif',
        ];
    }

    /**
     * Gets query for [[JamKerja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::class, ['id_jam_kerja' => 'id_jam_kerja']);
    }



    static function getHolidaysByMonth()
    {
        // Ambil tahun sekarang
        $currentYear = date('Y');

        // Ambil data libur untuk tahun sekarang
        $holidays = MasterHaribesar::find()
            ->select(['tanggal', 'nama_hari'])
            ->where(['YEAR(tanggal)' => $currentYear])
            ->asArray()
            ->all();

        // Inisialisasi array untuk mengelompokkan data
        $holidaysByMonth = [];

        // Proses data libur
        foreach ($holidays as $holiday) {
            // Ambil bulan dari tanggal
            $month = date('m', strtotime($holiday['tanggal']));

            // Cek apakah tanggal tersebut bukan hari Minggu
            if (date('N', strtotime($holiday['tanggal'])) !== '7') {
                // Tambahkan libur ke dalam array bulan yang sesuai
                if (!isset($holidaysByMonth[$month])) {
                    $holidaysByMonth[$month] = [];
                }
                $holidaysByMonth[$month][] = [
                    'tanggal' => $holiday['tanggal'],
                    'nama_hari' => $holiday['nama_hari'],
                ];
            }
        }

        return $holidaysByMonth;
    }
}
