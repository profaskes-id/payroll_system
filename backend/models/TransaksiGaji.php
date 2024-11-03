<?php

namespace backend\models;

use DateTime;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "transaksi_gaji".
 *
 * @property int $id_transaksi_gaji
 * @property string $nomer_identitas
 * @property string $nama
 * @property string $bagian
 * @property string $jabatan
 * @property int $jam_kerja
 * @property string $status_karyawan
 * @property int $periode_gaji_bulan
 * @property int $periode_gaji_tahun
 * @property int $jumlah_hari_kerja
 * @property int $jumlah_hadir
 * @property int $jumlah_sakit
 * @property int $jumlah_wfh
 * @property int $jumlah_cuti
 * @property float $gaji_pokok
 * @property string $jumlah_jam_lembur
 * @property float $lembur_perjam
 * @property float $total_lembur
 * @property float $jumlah_tunjangan
 * @property float $jumlah_potongan
 * @property float $potongan_wfh_hari
 * @property float $jumlah_potongan_wfh
 * @property float|null $gaji_diterima
 *
 * @property PeriodeGaji $periodeGajiBulan
 */
class TransaksiGaji extends \yii\db\ActiveRecord
{

    const STATUS_HADIR = 'H';
    const STATUS_SAKIT = 'S';
    const STATUS_WFH = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaksi_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomer_identitas', 'nama', 'bagian', 'jabatan', 'jam_kerja', 'status_karyawan', 'periode_gaji_bulan', 'periode_gaji_tahun', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti', 'gaji_pokok', 'jumlah_jam_lembur', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh'], 'required'],
            [['jam_kerja', 'periode_gaji_bulan', 'periode_gaji_tahun', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti'], 'integer'],
            [['gaji_pokok', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh', 'gaji_diterima'], 'number'],
            [['jumlah_jam_lembur'], 'safe'],
            [['nomer_identitas', 'nama', 'bagian', 'jabatan', 'status_karyawan'], 'string', 'max' => 255],
            [['periode_gaji_bulan', 'periode_gaji_tahun'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodeGaji::class, 'targetAttribute' => ['periode_gaji_bulan' => 'bulan', 'periode_gaji_tahun' => 'tahun']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_transaksi_gaji' => 'Id Transaksi Gaji',
            'nomer_identitas' => 'Nomer Identitas',
            'nama' => 'Nama',
            'bagian' => 'Bagian',
            'jabatan' => 'Jabatan',
            'jam_kerja' => 'Jam Kerja',
            'status_karyawan' => 'Status Karyawan',
            'periode_gaji_bulan' => 'Periode Gaji Bulan',
            'periode_gaji_tahun' => 'Periode Gaji Tahun',
            'jumlah_hari_kerja' => 'Jumlah Hari Kerja',
            'jumlah_hadir' => 'Jumlah Hadir',
            'jumlah_sakit' => 'Jumlah Sakit',
            'jumlah_wfh' => 'Jumlah Wfh',
            'jumlah_cuti' => 'Jumlah Cuti',
            'gaji_pokok' => 'Gaji Pokok',
            'jumlah_jam_lembur' => 'Jumlah Jam Lembur',
            'lembur_perjam' => 'Lembur Perjam',
            'total_lembur' => 'Total Lembur',
            'jumlah_tunjangan' => 'Jumlah Tunjangan',
            'jumlah_potongan' => 'Jumlah Potongan',
            'potongan_wfh_hari' => 'Potongan Wfh Hari',
            'jumlah_potongan_wfh' => 'Jumlah Potongan Wfh',
            'gaji_diterima' => 'Gaji Diterima',
        ];
    }

    /**
     * Gets query for [[PeriodeGajiBulan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodeGajiBulan()
    {
        return $this->hasOne(PeriodeGaji::class, ['bulan' => 'periode_gaji_bulan', 'tahun' => 'periode_gaji_tahun']);
    }


    public function getKaryawanData($id_karyawan, $bulan, $tahun)
    {
        return Karyawan::find()
            ->select(['karyawan.id_karyawan', 'karyawan.nama', 'karyawan.nomer_identitas', 'karyawan.kode_jenis_kelamin', 'karyawan.jenis_identitas', 'karyawan.kode_karyawan', 'thk.total_hari AS total_hari_kerja',])
            ->leftJoin('jam_kerja_karyawan jkk', 'karyawan.id_karyawan = jkk.id_karyawan')
            ->leftJoin('total_hari_kerja thk', "jkk.id_jam_kerja = thk.id_jam_kerja AND thk.bulan = :bulan AND thk.tahun = :tahun")
            ->where(['karyawan.id_karyawan' => $id_karyawan])
            ->params([':bulan' => $bulan, ':tahun' => $tahun])
            ->asArray()
            ->one();
    }

    public function getDataPekerjaan($id_karyawan)
    {
        $dataPekerjaan = DataPekerjaan::find()
            ->where(['id_karyawan' => $id_karyawan, 'is_aktif' => 1])
            ->asArray()
            ->one();

        if ($dataPekerjaan) {
            $dataPekerjaan['bagian'] = Bagian::findOne($dataPekerjaan['id_bagian']);
            $dataPekerjaan['statusKaryawan'] = MasterKode::find()->select(['nama_group', 'kode', 'nama_kode'])->where([
                'nama_group' => 'status-pekerjaan',
                'kode' => $dataPekerjaan['status']
            ])->asArray()->one();

            $dataPekerjaan['jabatan'] = MasterKode::find()->select(['nama_group', 'kode', 'nama_kode'])->where([
                'nama_group' => 'jabatan',
                'kode' => $dataPekerjaan['jabatan']
            ])->asArray()->one();
        }

        return $dataPekerjaan;
    }

    public function getAbsensiData($id_karyawan, $firstDayOfMonth, $lastDayOfMonth)
    {
        return Absensi::find()
            ->select([
                'total_hadir' => new Expression("SUM(CASE WHEN kode_status_hadir = :status_hadir THEN 1 ELSE 0 END)"),
                'total_sakit' => new Expression("SUM(CASE WHEN kode_status_hadir = :status_sakit THEN 1 ELSE 0 END)"),
                'total_wfh' => new Expression("SUM(CASE WHEN kode_status_hadir = :status_hadir AND is_wfh = :status_wfh THEN 1 ELSE 0 END)")
            ])
            ->where(['id_karyawan' => $id_karyawan])
            ->andWhere(['between', 'tanggal', $firstDayOfMonth, $lastDayOfMonth])
            ->params([
                ':status_hadir' => self::STATUS_HADIR,
                ':status_sakit' => self::STATUS_SAKIT,
                ':status_wfh' => self::STATUS_WFH
            ])
            ->asArray()
            ->one();
    }

    public function getTotalCutiKaryawan($id_karyawan, $firstDayOfMonth, $lastDayOfMonth)
    {

        $jam_kerja = 5;
        return array_sum(RekapCuti::find()->where(['id_karyawan' => $id_karyawan])->all());
        return PengajuanCuti::find()
            ->where([
                'id_karyawan' => $id_karyawan,
                'status' => 1
            ])
            ->andWhere([
                'or',
                [
                    'and',
                    ['>=', 'tanggal_mulai', $firstDayOfMonth],
                    ['<=', 'tanggal_mulai', $lastDayOfMonth]
                ],
                [
                    'and',
                    ['>=', 'tanggal_selesai', $firstDayOfMonth],
                    ['<=', 'tanggal_selesai', $lastDayOfMonth]
                ],
                [
                    'and',
                    ['<', 'tanggal_mulai', $firstDayOfMonth],
                    ['>', 'tanggal_selesai', $lastDayOfMonth]
                ]
            ])
            ->asArray()
            ->all();
    }

    public function getGajiPokok($id_karyawan)
    {
        return MasterGaji::find()->asArray()->where(['id_karyawan' => $id_karyawan])->one();
    }

    public function getJumlahJamLembur($id_karyawan, $firstDayOfMonth, $lastDayOfMonth)
    {
        $pengajuanLembur = PengajuanLembur::find()
            ->where([
                'id_karyawan' => $id_karyawan,
                'status' => 1
            ])
            ->andWhere(['>=', 'tanggal', $firstDayOfMonth])
            ->andWhere(['<=', 'tanggal', $lastDayOfMonth])
            ->asArray()  // Ini akan mengembalikan hasil dalam bentuk array
            ->all();

        $totalMenit = 0;

        foreach ($pengajuanLembur as &$lembur) {
            // Konversi jam ke DateTime untuk perhitungan yang lebih akurat
            $jamMulai = new DateTime($lembur['jam_mulai']);
            $jamSelesai = new DateTime($lembur['jam_selesai']);

            // Hitung selisih dalam menit
            $selisih = $jamSelesai->diff($jamMulai);
            $menitLembur = ($selisih->h * 60) + $selisih->i;

            $totalMenit += $menitLembur;

            // Tambahkan informasi durasi ke setiap entri lembur
            $lembur['durasi_menit'] = $menitLembur;
            $lembur['durasi_format'] = sprintf("%02d:%02d", floor($menitLembur / 60), $menitLembur % 60);
        }

        // Konversi total menit ke format jam:menit
        $jam = floor($totalMenit / 60);
        $menit = $totalMenit % 60;

        return [
            'detail_lembur' => $pengajuanLembur,
            'total_jam_lembur' => sprintf("%02d:%02d", $jam, $menit),
            'total_menit' => $totalMenit
        ];
    }

    public function getPeriodeGajiBulanFind($bulan, $tahun)
    {
        return PeriodeGaji::find()->asArray()->where(['bulan' => $bulan, 'tahun' => $tahun])->one();
    }

    public function getTunjangan($id_karyawan)
    {
        return TunjanganDetail::find()->where(['id_karyawan' => $id_karyawan])->asArray()->all();
    }
    public function getPotongan($id_karyawan)
    {
        return PotonganDetail::find()->where(['id_karyawan' => $id_karyawan])->asArray()->all();
    }
}
