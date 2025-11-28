<?php

namespace backend\models;

use amnah\yii2\user\models\User;
use DateTime;
use Exception;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Cast\Double;
use Symfony\Component\VarDumper\VarDumper;
use Yii;

/**
 * This is the model class for table "absensi".
 *
 * @property int $id_absensi
 * @property int $id_karyawan
 * @property string $tanggal
 * @property string|null $jam_masuk
 * @property string|null $jam_pulang
 * @property int $kode_status_hadir
 * @property string|null $keterangan
 * @property string|null $lampiran
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $alasan_terlambat
 *
 * @property Karyawan $karyawan
 */
class Absensi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'absensi';
    }

    /**
     * {@inheritdoc}
     */

    public $id_shift_kerja;
    public $foto_pulang;
    public function rules()
    {
        return [
            [['id_karyawan', 'tanggal', 'kode_status_hadir'], 'required'],
            [['id_karyawan', 'is_lembur', 'is_wfh', 'is_terlambat', 'is_24jam', 'id_shift_kerja', 'similarity'], 'integer'],
            [['tanggal', 'jam_masuk', 'jam_pulang', 'lama_terlambat', 'tanggal_pulang', 'id_shift', 'kelebihan_jam_pulang', 'id_shift_kerja', 'created_at', 'updated_at', 'created_by', 'updated_by', 'similarity'], 'safe'],
            [['keterangan', 'alasan_terlambat', 'alasan_terlalu_jauh', 'foto_masuk', 'foto_pulang'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['lampiran'], 'string', 'max' => 255],
            [['id_karyawan'], 'exist', 'skipOnError' => true, 'targetClass' => Karyawan::class, 'targetAttribute' => ['id_karyawan' => 'id_karyawan']],
            [['lampiran'], 'file', 'extensions' => 'png, jpg, jpeg, pdf, webp, avif', 'maxSize' => 1024 * 1024 * 2],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_absensi' => 'Id Absensi',
            'id_karyawan' => 'Id Karyawan',
            'tanggal' => 'Tanggal',
            'jam_masuk' => 'Jam Masuk',
            'jam_pulang' => 'Jam Pulang',
            'kode_status_hadir' => 'Kode Status Hadir',
            'keterangan' => 'Keterangan',
            'lampiran' => 'Lampiran',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'alasan_terlambat' => 'Alasan Terlambat',
            'alasan_terlalu_jauh' => 'alasan terlalu jauh',
            'is_lembur' => 'Apakah Lembur',
            'is_wfh' => 'Apakah WFH',
            'is_terlambat' => 'Apakah Terlambat',
            'lama_terlambat' => 'Lama Terlambat',
            'is_24jam' => 'Is 24jam',
            'id_shift' => 'id_shift',
            'tanggal_pulang' => 'Tanggal Pulang',
            'id_shift_kerja' => 'id shift kerja',
            'kelebihan_jam_pulang' => 'kelebihan jam pulang',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'foto_masuk' => 'foto input',
            'foto_pulang' => 'foto input',
            'similarity' => 'Similarity',
        ];
    }

    /**
     * Gets query for [[Karyawan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKaryawan()
    {
        return $this->hasOne(Karyawan::class, ['id_karyawan' => 'id_karyawan']);
    }

    public function getStatusHadir()
    {
        return $this->hasOne(MasterKode::class, ['kode' => 'kode_status_hadir'])->onCondition(['nama_group' => 'status-hadir']);
    }

    static function getAllAbsensiFromFirstAndLastMonth($absensi, $firstDayOfMonth, $lastDayOfMonth)
    {

        $absensi = $absensi::find()
            ->select([
                'absensi.id_karyawan',
                'MIN(absensi.jam_masuk) AS jam_masuk', // Ambil jam masuk paling awal
                'MIN(absensi.jam_pulang) AS jam_pulang', // Ambil jam masuk paling awal
                'absensi.tanggal',
                'absensi.is_lembur',
                'absensi.is_wfh',
                'sk.nama_shift as id_shift',
                'absensi.is_24jam',
                'absensi.kode_status_hadir',
                'absensi.is_terlambat',
                'absensi.lama_terlambat',
                'jkk.id_jam_kerja',
                'MIN(jdk.jam_masuk) AS jam_masuk_kerja', // Ambil jam masuk kerja paling awal
                'jdk.nama_hari'
            ])
            ->asArray()
            ->leftJoin('jam_kerja_karyawan jkk', 'jkk.id_karyawan = absensi.id_karyawan')
            ->leftJoin('shift_kerja sk', 'sk.id_shift_kerja = absensi.id_shift')
            ->leftJoin('jadwal_kerja jdk', 'jkk.id_jam_kerja = jdk.id_jam_kerja AND jdk.nama_hari = DAYOFWEEK(absensi.tanggal) - 1')
            ->andWhere(['>=', 'absensi.tanggal', $firstDayOfMonth])
            ->andWhere(['<=', 'absensi.tanggal', $lastDayOfMonth])
            ->groupBy('absensi.id_karyawan, absensi.tanggal, absensi.is_lembur, absensi.is_24jam, absensi.is_wfh, absensi.kode_status_hadir, absensi.is_terlambat,absensi.lama_terlambat,  jkk.id_jam_kerja, jdk.nama_hari, sk.nama_shift')

            ->all();


        return $absensi;
    }



    static function getTanggalFromFirstAndLastMonth($firstDayOfMonth, $lastDayOfMonth)
    {
        $tanggal_bulanan = array();

        // Ubah ke timestamp jika belum
        $start = strtotime($firstDayOfMonth);
        $end = strtotime($lastDayOfMonth);

        while ($start <= $end) {
            $tanggal_bulanan[] = date('Y-m-d', $start); // atau 'd' kalau hanya ingin tanggal
            $start = strtotime("+1 day", $start);
        }

        return $tanggal_bulanan;
    }


    static function getAllDetailDataKaryawan($karyawan)
    {
        $data = $karyawan::find()
            ->select([
                'karyawan.id_karyawan',
                'karyawan.nama',
                'karyawan.kode_karyawan',
                'bg.id_bagian',
                'bg.nama_bagian',
                'dp.jabatan',
                'mk.nama_kode as jabatan',
                'thk.total_hari',
                'jk.nama_jam_kerja',
                'jkk.is_shift',
            ])
            ->asArray()
            ->where(['karyawan.is_aktif' => 1])
            ->leftJoin('jam_kerja_karyawan jkk', 'jkk.id_karyawan = karyawan.id_karyawan')
            ->leftJoin('total_hari_kerja thk', 'thk.id_jam_kerja = jkk.id_jam_kerja ')
            ->leftJoin('jam_kerja jk', 'jk.id_jam_kerja = jkk.id_jam_kerja')
            ->leftJoin('{{%data_pekerjaan}} dp', 'karyawan.id_karyawan = dp.id_karyawan')
            ->leftJoin('{{%bagian}} bg', 'dp.id_bagian = bg.id_bagian')
            ->leftJoin('{{%master_kode}} mk', 'mk.nama_group = "jabatan" and dp.jabatan = mk.kode')
            ->orderBy(['bg.id_bagian' => SORT_DESC, 'karyawan.nama' => SORT_ASC])
            ->all();

        // Definisikan warna untuk masing-masing id_bagian
        $colorList = [
            '#A5D6A7', // hijau pastel
            '#FFF59D', // kuning pastel
            '#90CAF9', // biru pastel
            '#FFCC80', // oranye pastel
            '#CE93D8', // ungu pastel
            '#B0BEC5', // abu pastel
            '#F48FB1', // pink pastel
            '#AED581', // lime pastel
            '#80DEEA', // cyan pastel
        ];

        $usedColors = [];    // mapping id_bagian => color
        $colorIndex = 0;     // urutan warna yang sedang digunakan

        foreach ($data as &$item) {
            $idBagian = $item['id_bagian'] ?? 'null'; // gunakan string agar null juga bisa dipetakan

            if (!isset($usedColors[$idBagian])) {
                // Ambil warna dari daftar, atau fallback ke warna default
                $usedColors[$idBagian] = $colorList[$colorIndex] ?? '#F0F0F0';
                $colorIndex++;
            }

            $item['color'] = $usedColors[$idBagian];
        }
        unset($item); // clean up reference 


        return $data;
    }



    static function getIncludeKaryawanAndAbsenData($dataKaryawan, $absensi, $firstDayOfMonth, $lastDayOfMonth, $tanggal_bulanan)
    // static function getIncludeKaryawanAndAbsenData($bulan, $tahun, $dataKaryawan, $absensi, $firstDayOfMonth, $lastDayOfMonth)
    {
        $SETTINGAN_LEMBUR = SettinganUmum::find()->where(['kode_setting' => Yii::$app->params['ajukan_lembur']])->asArray()->one();

        if ($SETTINGAN_LEMBUR) {

            $lemburPerKaryawan = [];
            // jika diajukan
            if ($SETTINGAN_LEMBUR['nilai_setting'] == 1) {
                $lemburData = PengajuanLembur::find()
                    ->where(['between', 'tanggal', $firstDayOfMonth, $lastDayOfMonth])
                    ->andWhere(['!=', 'status', 2]) // status selain 2
                    ->asArray()
                    ->all();


                foreach ($lemburData as $lembur) {
                    $id = $lembur['id_karyawan'];
                    $jam = $lembur['hitungan_jam'] ?? 0;

                    if (!isset($lemburPerKaryawan[$id])) {
                        $lemburPerKaryawan[$id] = [
                            'total_lembur' => 0,
                            'jumlah_jam_lembur' => 0,
                        ];
                    }

                    $lemburPerKaryawan[$id]['total_lembur'] += 1;
                    $lemburPerKaryawan[$id]['jumlah_jam_lembur'] += (float) $jam;
                }
            } else {

                // jika tidak diajukan
                $absens = Absensi::find()
                    ->where(['between', 'tanggal', $firstDayOfMonth, $lastDayOfMonth])
                    ->asArray()
                    ->all();

                foreach ($absens as $absen) {
                    $id = $absen['id_karyawan'];

                    if (!isset($lemburPerKaryawan[$id])) {
                        $lemburPerKaryawan[$id] = [
                            'total_lembur' => 0, // jumlah hari lembur
                            'jumlah_jam_lembur' => 0, // total menit
                        ];
                    }

                    if (!empty($absen['kelebihan_jam_pulang'])) {
                        // Konversi waktu (HH:MM atau HH:MM:SS) ke total menit
                        $waktu = $absen['kelebihan_jam_pulang'];
                        $parts = explode(':', $waktu);

                        $jam = isset($parts[0]) ? (int)$parts[0] : 0;
                        $menit = isset($parts[1]) ? (int)$parts[1] : 0;

                        $totalMenit = ($jam * 60) + $menit;

                        $lemburPerKaryawan[$id]['total_lembur'] += 1; // hari lembur
                        $lemburPerKaryawan[$id]['jumlah_jam_lembur'] += (float) $totalMenit / 60;
                    }
                }
            }
        }




        // ====================================================
        $hasil = [];
        // $totalHari = date('t', mktime(0, 0, 0, $bulan, 1, $tahun));
        $totalHari = self::getTotalHari($firstDayOfMonth, $lastDayOfMonth);

        $totalTerlambat = 0;
        $totalHadir = 0;
        $detikTerlambat = 0;

        $keterlambatanPerTanggal = array_fill(1, $totalHari, 0);

        foreach ($dataKaryawan as $karyawan) {


            $nama_jam_kerja = $karyawan["nama_jam_kerja"];
            if ($nama_jam_kerja == null) {
                continue;
            } else {

                $karyawanData = [
                    [
                        "id_karyawan" => $karyawan["id_karyawan"],
                        "nama" => $karyawan["nama"],
                        "kode_karyawan" => $karyawan["kode_karyawan"],
                        "id_bagian" => $karyawan["id_bagian"],
                        "bagian" => $karyawan["nama_bagian"],
                        "jabatan" => $karyawan["jabatan"],
                        'color' => $karyawan['color'],
                    ],
                ];


                // $absensiRecord = [];
                for ($i = 1; $i <= $totalHari; $i++) {
                    $tanggal = $tanggal_bulanan[$i - 1];
                    $absensiRecord = array_filter($absensi, function ($record) use ($karyawan, $tanggal) {
                        return $record['id_karyawan'] == $karyawan['id_karyawan'] && $record['tanggal'] == $tanggal;
                    });



                    $statusHadir = null;
                    $is_lembur = 0;
                    $is_wfh = 0;
                    $is_24jam = 0;
                    $is_terlambat = 0;
                    $lama_terlambat = 0;
                    $jamMasukKaryawan = null;
                    $jamMasukKantor = null;
                    $jam_pulang = null;
                    $id_shift = null;


                    if (!empty($absensiRecord)) {
                        $record = array_values($absensiRecord)[0];
                        $statusHadir = $record['kode_status_hadir'];
                        $is_24jam = $record['is_24jam'];
                        $is_lembur = $record['is_lembur'];
                        $is_wfh = $record['is_wfh'];
                        $jamMasukKaryawan = $record['jam_masuk'];
                        $jamMasukKantor = $record['jam_masuk_kerja'] ?? null;
                        $jam_pulang = $record['jam_pulang'] ?? null;
                        $id_shift = $record['id_shift'] ?? null;


                        if ($statusHadir == 'H') {

                            if ($record['is_terlambat'] == 1 && $record['is_lembur'] == 0 && $record['is_wfh'] == 0) {
                                $is_terlambat = 1;
                                $lama_terlambat = $record['lama_terlambat'] ?? "00:00:00";
                                $lama_terlambat = is_string($lama_terlambat) && $lama_terlambat !== '' ? $lama_terlambat : "00:00:00";
                                $jamMenitDetik = explode(':', $lama_terlambat);

                                $detikTerlambat += ($jamMenitDetik[0] * 3600) + ($jamMenitDetik[1] * 60) + $jamMenitDetik[2];
                                $totalTerlambat++;
                                // $selisihDetik = $jamMasuk - $jamMasukKerja;
                                $keterlambatanPerTanggal[$i] = ($keterlambatanPerTanggal[$i] ?? 0) + 1;
                            }
                            $totalHadir++;
                        }
                    }
                    if ($statusHadir == 'DL') {
                        $totalHadir++;
                    }
                    if ($statusHadir == 'wfh' || $statusHadir == 'WFH') {
                        $totalHadir++;
                    }
                    if ($statusHadir == 'c' || $statusHadir == 'C') {
                        $totalHadir++;
                    }

                    $karyawanData[] = [
                        'jam_masuk_kantor' => $jamMasukKantor,
                        'jam_masuk_karyawan' => $jamMasukKaryawan,
                        'jam_pulang' => $jam_pulang,
                        'id_shift' => $id_shift,
                        'status_hadir' => $statusHadir,
                        'is_lembur' => $is_lembur,
                        'is_wfh' => $is_wfh,
                        'is_24jam' => $is_24jam,
                        'is_terlambat' => $is_terlambat,
                        'lama_terlambat' => $lama_terlambat,
                        'total_terlambat_hari_ini' => $keterlambatanPerTanggal[$i] ?? 0, // Tambahkan info keterlambatan per tanggal
                    ];
                }







                $karyawanData[] = [
                    'status_hadir' => null,
                    'jam_masuk_karyawan' => null,
                    'jam_masuk_kantor' => null,
                    'total_hadir' => $totalHadir,
                ];
                $karyawanData[] = [
                    'status_hadir' => null,
                    'jam_masuk_karyawan' => null,
                    'jam_masuk_kantor' => null,
                    'total_terlambat' => $totalTerlambat,
                ];
                $karyawanData[] = [
                    'status_hadir' => null,
                    'jam_masuk_karyawan' => null,
                    'jam_masuk_kantor' => null,
                    'detik_terlambat' => $detikTerlambat,
                ];
                $idKaryawan = $karyawan['id_karyawan'];
                $lemburInfo = $lemburPerKaryawan[$idKaryawan] ?? ['total_lembur' => 0, 'jumlah_jam_lembur' => 0];

                $karyawanData[] = [
                    'status_hadir' => null,
                    'jam_masuk_karyawan' => null,
                    'jam_masuk_kantor' => null,
                    'total_lembur' => $lemburInfo['total_lembur'] ?? 0,
                ];
                $karyawanData[] = [
                    'status_hadir' => null,
                    'jam_masuk_karyawan' => null,
                    'jam_masuk_kantor' => null,
                    'jumlah_jam_lembur' => $lemburInfo['jumlah_jam_lembur'] ?? 0,
                ];
            }



            if (empty($nama_jam_kerja)) {
                Yii::$app->session->setFlash('error', 'Tolong isi data jam kerja dari ' . strtoupper($karyawanData[0]['nama']) . ' terlebih dahulu , untuk saat ini data jam kerja adalah ' . "5 hari kerja yang diisi secara default");
            }
            $string = $nama_jam_kerja ??  "5 Hari Kerja";



            $work_days_type = match (true) {
                str_contains($string, "2") => 2,
                str_contains($string, "3") => 3,
                str_contains($string, "4") => 4,
                str_contains($string, "5") => 5,
                str_contains($string, "6") => 6,
                str_contains($string, "7") => 7,
                default => throw new Exception("Invalid work days type")
            };


            $today = new DateTime();
            $today->modify('+1 day');
            $currentMonth = $today->format('m');
            $currentYear = $today->format('Y');
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            $validDates = [];
            $tomorrowDay = (int)$today->format('d');

            for ($day = $tomorrowDay; $day <= $daysInMonth; $day++) {
                $date = new DateTime("$currentYear-$currentMonth-$day");
                $string = $nama_jam_kerja ??  "5 Hari Kerja";
                $work_days_type = match (true) {
                    str_contains($string, "2") => 2,
                    str_contains($string, "3") => 3,
                    str_contains($string, "4") => 4,
                    str_contains($string, "5") => 5,
                    str_contains($string, "6") => 6,
                    str_contains($string, "7") => 7,
                    default => throw new Exception("Invalid work days type")
                };
                $dayOfWeek = $date->format('N');

                if ($work_days_type === 5 && ($dayOfWeek == 6 || $dayOfWeek == 7)) {
                    continue;
                }
                if ($work_days_type === 6 && $dayOfWeek == 7) {
                    continue;
                }
                if ($work_days_type === 4 && ($dayOfWeek == 5 || $dayOfWeek == 6 || $dayOfWeek == 7)) {
                    continue;
                }

                $validDates[] = $date->format('Y-m-d');
            }



            $hasil[] = $karyawanData;
            $totalTerlambat = 0;
            $totalHadir = 0;
            $detikTerlambat = 0;
        }

        return [
            'hasil' => $hasil,
            'keterlambatanPerTanggal' => $keterlambatanPerTanggal
        ];
        // ====================================================
    }


    static function getAbsnesiDataWereHadir($model, $firstDayOfMonth, $lastDayOfMonth)
    {
        return $model::find()
            ->select(['absensi.id_absensi', 'absensi.tanggal', 'absensi.kode_status_hadir'])
            ->asArray()
            ->leftJoin('{{%karyawan}} k', 'absensi.id_karyawan = k.id_karyawan')
            ->where(['kode_status_hadir' => 'H'])
            ->andWhere(['k.is_aktif' => 1])
            ->orderBy(['absensi.tanggal' => SORT_ASC])
            ->andWhere(['between', 'absensi.tanggal', "$firstDayOfMonth", "$lastDayOfMonth"])
            ->all();
    }

    public static function getTotalHari($firstDayOfMonth, $lastDayOfMonth)
    {
        $start = new DateTime($firstDayOfMonth);
        $end = new DateTime($lastDayOfMonth);

        $diff = $start->diff($end);
        return $diff->days + 1;
    }


    public function getCreateBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getUpdateBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getShift()
    {
        return $this->hasOne(ShiftKerja::class, ['id_shift_kerja' => 'id_shift']);
    }
}
