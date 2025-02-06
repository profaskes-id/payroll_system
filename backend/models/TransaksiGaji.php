<?php

namespace backend\models;

use DateTime;
use Yii;
use yii\db\Expression;
use yii\db\Query;

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
 * @property int $periode_gaji
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


    public function rules()
    {
        return [
            [['kode_karyawan', 'nomer_identitas', 'nama', 'bagian', 'jabatan', 'jam_kerja', 'status_karyawan', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti', 'gaji_pokok', 'jumlah_jam_lembur', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh', 'jumlah_terlambat', 'potongan_terlambat_permenit', 'jumlah_potongan_terlambat'], 'required'],
            [['jam_kerja', 'periode_gaji', 'jumlah_hari_kerja', 'jumlah_hadir', 'jumlah_sakit', 'jumlah_wfh', 'jumlah_cuti', 'jumlah_tidak_hadir'], 'integer'],
            [['gaji_pokok', 'lembur_perjam', 'total_lembur', 'jumlah_tunjangan', 'jumlah_potongan', 'potongan_wfh_hari', 'jumlah_potongan_wfh', 'potongan_tidak_hadir_hari', 'jumlah_potongan_tidak_hadir', 'potongan_terlambat_permenit', 'jumlah_potongan_terlambat', 'gaji_diterima', 'tunjangan_lainnya', 'potongan_lainnya'], 'number'],
            [['jumlah_jam_lembur', 'jumlah_terlambat'], 'safe'],
            [['kode_karyawan', 'nomer_identitas', 'nama', 'bagian', 'jabatan', 'status_karyawan', 'keterangan_tunjangan_lainnya', 'keterangan_potongan_lainnya'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_transaksi_gaji' => 'Id Transaksi Gaji',
            'kode_karyawan' => 'Kode Karyawan',
            'nomer_identitas' => 'Nomer Identitas',
            'nama' => 'Nama',
            'bagian' => 'Bagian',
            'jabatan' => 'Jabatan',
            'jam_kerja' => 'Jam Kerja',
            'status_karyawan' => 'Status Karyawan',
            'periode_gaji' => 'Periode Gaji',
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
            'jumlah_tidak_hadir' => 'Jumlah Tidak Hadir',
            'potongan_tidak_hadir_hari' => 'Potongan Tidak Hadir Hari',
            'jumlah_potongan_tidak_hadir' => 'Jumlah Potongan Tidak Hadir',
            'jumlah_terlambat' => 'Jumlah Terlambat',
            'potongan_terlambat_permenit' => 'Potongan Terlambat Permenit',
            'jumlah_potongan_terlambat' => 'Jumlah Potongan Terlambat',
            'gaji_diterima' => 'Gaji Diterima',
            'keterangan_tunjangan_lainnya' => 'Keterangan Tunjangan Lainnya',
            'keterangan_potongan_lainnya' => 'Keterangan Potongan Lainnya',
            'tunjangan_lainnya' => 'Tunjangan Lainnya',
            'potongan_lainnya' => 'Potongan Lainnya',
        ];
    }


    public function getTransaksiData()
    {
        $data =  TransaksiGaji::find()->asArray()->select(['id_transaksi_gaji', 'nama'])->groupBy('nama')->all();
    }


    public function getKaryawanData($id_karyawan, $id_periode_gaji)
    {


        $data =  Karyawan::find()
            ->select(['karyawan.id_karyawan', 'karyawan.nama', 'karyawan.nomer_identitas', 'karyawan.kode_jenis_kelamin', 'karyawan.jenis_identitas', 'karyawan.kode_karyawan',  'jkk.id_jam_kerja'])
            ->leftJoin('jam_kerja_karyawan jkk', 'karyawan.id_karyawan = jkk.id_karyawan')
            ->leftJoin('jam_kerja jk', 'karyawan.id_karyawan = jkk.id_karyawan')
            ->where(['karyawan.id_karyawan' => $id_karyawan])
            ->asArray()
            ->one();

        $totalHariKerja = TotalHariKerja::find()
            ->where(['id_jam_kerja' => $data['id_jam_kerja'], 'id_periode_gaji' => $id_periode_gaji])
            ->asArray()
            ->one();


        //jika total hari kerja == null kembalikan dengan set session error
        if ($totalHariKerja == null) {
            Yii::$app->session->setFlash('error', 'Silahkan Cek Data <a href="/panel/total-hari-kerja" style="text-decoration: underline; color: blue" target="_blank" >Total Hari Kerja</a> Total Hari Kerja Dan Data Periode Gaji Terlebih Dahulu');
            $data['total_hari_kerja'] = 0;
            return $data;
        }
        $data['total_hari_kerja'] = $totalHariKerja['total_hari'];
        return $data;
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

        $dataAbsensi = Absensi::find()
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
        return $dataAbsensi;
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

        foreach ($pengajuanLembur as $key => $value) {
            $durasi = $value['durasi']; // Misalnya "08:00:00"
            list($jam, $menit, $detik) = explode(':', $durasi);
            $totalMenit += ($jam * 60) + $menit; // Menambahkan jam dalam menit dan menit
        }


        return [
            'detail_lembur' => $pengajuanLembur,
            'total_menit' => $totalMenit
        ];
    }

    public function getTunjangan($id_karyawan, $is_sum = false)
    {
        $data = TunjanganDetail::find()->where(['id_karyawan' => $id_karyawan])->asArray()->all();
        if ($is_sum) {
            return array_sum(array_column($data, 'jumlah'));
        } else {
            return $data;
        }
    }


    public function getPotongan($id_karyawan, $is_sum = false)
    {
        $data = PotonganDetail::find()->where(['id_karyawan' => $id_karyawan])->asArray()->all();
        if ($is_sum) {
            return array_sum(array_column($data, 'jumlah'));
        } else {
            return $data;
        }
    }



    public function deleteGajiTunjanganDanPotongan($idTransaksiGaji)
    {
        // Mulai transaksi untuk memastikan konsistensi
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            // Menghapus data dari tabel gaji_tunjangan berdasarkan id_transaksi_gaji
            $tunjanganDeleted = Yii::$app->db->createCommand()
                ->delete('gaji_tunjangan', ['id_transaksi_gaji' => $idTransaksiGaji])
                ->execute();

            // Menghapus data dari tabel gaji_potongan berdasarkan id_transaksi_gaji
            $potonganDeleted = Yii::$app->db->createCommand()
                ->delete('gaji_potongan', ['id_transaksi_gaji' => $idTransaksiGaji])
                ->execute();

            // Jika kedua query penghapusan berhasil, commit transaksi
            if ($tunjanganDeleted && $potonganDeleted) {
                $transaction->commit();
                return true; // Penghapusan berhasil
            } else {
                // Jika ada yang gagal, rollback transaksi
                $transaction->rollBack();
                return false; // Penghapusan gagal
            }
        } catch (\Exception $e) {
            // Jika ada exception, rollback transaksi
            $transaction->rollBack();
            // Menangkap error jika terjadi kesalahan
            \Yii::error("Error while deleting gaji_tunjangan and gaji_potongan: " . $e->getMessage());
            return false; // Penghapusan gagal
        }
    }



    public function getPeriodeGajiOne($id_periode_gaji)
    {

        return PeriodeGaji::find()->asArray()->where(['id_periode_gaji' => $id_periode_gaji])->one();
    }

    public function getPeriodeGajidpw()
    {
        $gaji =  PeriodeGaji::find()->asArray()->all();


        $data = [];

        $tanggal = new Tanggal();
        foreach ($gaji as $key => $value) {
            $data[] = [
                'id_periode_gaji' => $value['id_periode_gaji'],
                'bulan' => $value['bulan'],
                'tahun' => $value['tahun'],
                'tampilan' => $tanggal->getBulan($value['bulan']) . ' '  . $value['tahun']
            ];
        }

        return $data;
    }

    public function getTotalCutiKaryawan($karyawan, $firstDayOfMonth, $lastDayOfMonth)
    {
        $id_karyawan = $karyawan['id_karyawan'];


        $jamKerjaKaryawan = JamKerjaKaryawan::find()->where(['id_karyawan' => $id_karyawan])->one();

        $containsNumber = strpos($jamKerjaKaryawan->jamKerja->nama_jam_kerja, preg_match('/\d+/', "5", $matches) ? $matches[0] : '') !== false;
        $data = PengajuanCuti::find()
            ->where(['id_karyawan' => $id_karyawan, 'status' => 1])
            ->andWhere(['between', 'tanggal_mulai', $firstDayOfMonth, $lastDayOfMonth])->asArray()->all();

        $fix = [];
        foreach ($data as $key => $value) {
            $hari_kerja = $this->hitungHariKerja($value['tanggal_mulai'], $value['tanggal_selesai'], $containsNumber);
            $fix[] = $hari_kerja;
        }

        $totalCuti = array_sum($fix);
        return $totalCuti;
    }

    public  function hitungHariKerja($tanggal_mulai, $tanggal_selesai, $containsNumber)
    {
        // Konversi tanggal menjadi timestamp
        $timestamp_mulai = strtotime($tanggal_mulai);
        $timestamp_selesai = strtotime($tanggal_selesai);

        // Inisialisasi variabel untuk menghitung hari kerja
        $hari_kerja = 0;

        // Loop melalui semua hari antara tanggal mulai dan tanggal selesai
        for ($timestamp = $timestamp_mulai; $timestamp <= $timestamp_selesai; $timestamp += 86400) { // 86400 detik = 1 hari
            // Ambil nama hari dalam seminggu (contoh: "Sunday", "Monday", dll.)
            $hari = date('l', $timestamp);

            if ($containsNumber) {
                if ($hari != 'Saturday' && $hari != 'Sunday') {
                    $hari_kerja++;
                }
            } else {
                if ($hari != 'Sunday') {
                    $hari_kerja++;
                }
            }
            // Periksa apakah hari tersebut bukan Sabtu atau Minggu
        }

        return $hari_kerja;
    }


    public function getTotalTerlambat($id_karyawan, $firstDayOfMonth, $lastDayOfMonth)
    {

        // Ambil data jam kerja karyawan
        $jamKerjakaryawan = (new \yii\db\Query())
            ->select(['jkk.*', 'jd.id_jam_kerja', 'jd.nama_hari', 'jd.jam_masuk'])
            ->from(['jkk' => 'jam_kerja_karyawan'])
            ->leftJoin(['jd' => 'jadwal_kerja'], 'jkk.id_jam_kerja = jd.id_jam_kerja')
            ->where(['jkk.id_karyawan' => $id_karyawan])
            ->all();

        // Ambil data absensi
        $data = Absensi::find()
            ->select(['tanggal', 'jam_masuk'])
            ->where(['id_karyawan' => $id_karyawan])
            ->andWhere(['between', 'tanggal', $firstDayOfMonth, $lastDayOfMonth])
            ->andWhere(['kode_status_hadir' => self::STATUS_HADIR])
            ->andWhere(['is_lembur' => 0])
            ->asArray()
            ->all();


        $totalTerlambarDetik = 0;
        // Buat array untuk menyimpan jam masuk berdasarkan nama hari
        $jamMasukByHari = [];
        foreach ($jamKerjakaryawan as $jamKerja) {
            $jamMasukByHari[$jamKerja['nama_hari']] = $jamKerja['jam_masuk'];
        }

        // Loop melalui data absensi dan tambahkan jam masuk
        foreach ($data as $key => $value) {
            // Dapatkan nama hari dari tanggal
            $nama_hari = date('N', strtotime($value['tanggal']));
            // Cek apakah nama hari ada di jamMasukByHari
            if (isset($jamMasukByHari[$nama_hari])) {
                $jamMasukKantor = strtotime($jamMasukByHari[$nama_hari]);
                $jamKeryawanMasuk = strtotime($value['jam_masuk']);
                if ($jamKeryawanMasuk > $jamMasukKantor) {
                    $selisih = $jamKeryawanMasuk - $jamMasukKantor;
                    $totalTerlambarDetik += $selisih;
                }
            }
        }


        $jam = floor($totalTerlambarDetik / 3600); // Menghitung jam
        $menit = floor(($totalTerlambarDetik % 3600) / 60); // Menghitung menit
        $detik = $totalTerlambarDetik % 60; // Menghitung detik
        $formattedTime = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
        return $formattedTime;
    }
}
