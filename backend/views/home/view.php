<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Absensi $model */

$this->title = "Histori";
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$masterKode = \backend\models\MasterKode::find()->where(['nama_group' => Yii::$app->params['status-hadir']])->andWhere(['!=', 'status', 0])->orderBy(['urutan' => SORT_ASC])->all();

// dd($masterKode);
?>
<div class="absensi-view container mx-auto relative min-h-[90dvh] px    -5 ">


    <div class="col-span-12  w-full   px-5 py-5   ">
        <div class="flex justify-between items-center">
            <div class="flex items-start justify-center flex-col text-2xl">
                <?= $this->render('@backend/views/components/fragment/_back-button'); ?>

            </div>
            <div>
                <div class="w-[50px] h-[50px] rounded-full bg-[url(https://plus.unsplash.com/premium_photo-1664870883044-0d82e3d63d99?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D)] bg-cover bg-center">
                </div>
            </div>
        </div>
    </div>




    <form id="my-form" action="" method="post">
        <div class="w-full ">
            <input name="tanggal_searh" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  " placeholder="Select date">
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('default-datepicker');
            var form = document.getElementById('my-form');

            window.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    form.submit();
                }
            })

        });
    </script>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50  ">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Kehadiran
                    </th>
                    <th scope="col" class="px-6 py-3">
                        tanggal
                    </th>
                    <th scope="col" class="px-6 py-3">
                        jam_masuk
                    </th>
                    <th scope="col" class="px-6 py-3">
                        jam_pulang
                    </th>
                    <th scope="col" class="px-6 py-3">
                        keterangan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        lampiran
                    </th>
                </tr>
            </thead>
            <tbody>


                <?php foreach ($absensi as $key => $value) : ?>
                    <tr class="odd:bg-white odd: even:bg-gray-50 even: border-b ">


                        <td class="px-6 py-4">
                            <?php foreach ($masterKode as $key => $item) : ?>

                                <?php if ($value['kode_status_hadir'] == $item['kode']) : ?>
                                    <?= $item['nama_kode'] ?>
                                <?php endif ?>

                            <?php endforeach ?>

                        </td>
                        <td class="px-6 py-4">
                            <?php echo $value['tanggal'] ?>
                        </td>
                        <td class="px-6 py-4">

                            <?php echo $value['jam_masuk'] ?>
                        </td>
                        <td class="px-6 py-4">

                            <?php echo $value['jam_masuk'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo $value['keterangan'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo $value['lampiran'] ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>


</div>
<div class="lg:hidden">

    <?= $this->render('@backend/views/components/_footer'); ?>
</div>