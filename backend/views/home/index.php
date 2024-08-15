    <?php

    use backend\assets\AppAsset;
    use backend\models\Absensi;
    use yii\bootstrap5\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;

    /** @var yii\web\View $this */
    /** @var yii\data\ActiveDataProvider $dataProvider */


    $this->title = 'Absensis';

    $this->params['breadcrumbs'][] = $this->title;

    ?>




    <section class="grid grid-cols-10  relative overflow-x-hidden min-h-[90dvh]">



        <!-- drawer init and toggle -->
        <div class="text-center">
            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" type="button" data-drawer-target="drawer-example" data-drawer-show="drawer-example" aria-controls="drawer-example">
                Show drawer
            </button>
        </div>

        <!-- drawer component -->
        <div id="drawer-example" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-label">
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>Info</h5>
            <button type="button" data-drawer-hide="drawer-example" aria-controls="drawer-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close menu</span>
            </button>

            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Supercharge your hiring by taking advantage of our <a href="#" class="text-blue-600 underline dark:text-blue-500 hover:no-underline">limited-time sale</a> for Flowbite Docs + Job Board. Unlimited access to over 190K top-ranked candidates and the #1 design job board.</p>
            <div class="grid grid-cols-2 gap-4">
                <a href="#" class="px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Learn more</a>
                <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get access <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg></a>
            </div>
        </div>






        <div class="fixed w-1/2 bottom-0 left-1/2 -translate-x-1/2 z-40 hidden lg:block  ">
            <?= $this->render('@backend/views/components/_footer'); ?>
        </div>


        <div class="col-span-12 w-full   px-5 pt-5 ">

            <?= $this->render('@backend/views/components/_header'); ?>

            <div class=" grid grid-cols-12 place-items-center">
                <div class="col-span-12   w-full">

                    <?= $this->render('@backend/views/components/fragment/_time'); ?>

                    <div class="mt-10 grid place-items-center">




                        <?php if (count($absensiToday) > 0) : ?>
                            <?php if (empty($absensiToday[0]->jam_pulang)) : ?>
                                <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-pulang']]); ?>

                                <button class="all-none" type="submit">
                                    <div class="flex flex-col -space-y-10 w-[225px] h-[225px] bg-gradient-to-r from-[#CE1705] to-[#EF0802] shadow-2xl shadow-[#D51405] rounded-full lg:rounded-3xl ">
                                        <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => '-mt-4 w-full scale-[0.6] h-full object-cover ']) ?>
                                        <p class="font-bold text-white m-0">Absen Keluar</p>
                                    </div>
                                </button>
                                <?php ActiveForm::end(); ?>
                            <?php else : ?>
                                <button class="all-none" type="button" disabled>
                                    <div class="flex flex-col -space-y-10 w-[225px] h-[225px] bg-gradient-to-r from-[#686161] to-[#2b2b2b] shadow-2xl shadow-[#9b9b9b] rounded-full lg:rounded-3xl ">
                                        <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => '-mt-4 w-full scale-[0.6] h-full object-cover ']) ?>
                                        <p class="font-bold text-white m-0">Selsai</p>
                                    </div>
                                </button>
                            <?php endif ?>


                        <?php else : ?>
                            <?php $formAbsen = ActiveForm::begin(['method' => 'post', 'id' => 'my-form',  'action' => ['home/absen-masuk']]); ?>
                            <button class="all-none" type="submit">
                                <div class="flex flex-col -space-y-10 w-[225px] h-[225px] bg-gradient-to-r from-[#EB5A2B] to-[#EA792B] shadow-xl shadow-[#EB5A2B] rounded-full lg:rounded-3xl ">
                                    <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => '-mt-4 w-full scale-[0.6] h-full object-cover ']) ?>
                                    <p class="font-bold text-white m-0">Absen Masuk</p>
                                </div>
                            </button>

                            <?php ActiveForm::end(); ?>
                        <?php endif ?>

                    </div>



                </div>



            </div>
        </div>


        <!-- ? mobile izin -->
        <div class="flex   justify-around my-10 w-screen relative ">
            <a href="/panel/home/create">

                <div class="grid place-items-center">
                    <div class="w-[60px] h-[60px]  border border-gray rounded-full grid place-items-center">
                        <div class="font-black text-white w-8 h-8 text-center grid place-items-center rounded-full bg-rose-500">
                            <span class="w-5 h-1 bg-white rounded-xl"></span>
                        </div>
                    </div>
                    <p class="mt-2 font-medium capitalize">Izin Tidak hadir</p>
                </div>
            </a>
            <?= Html::a('
              <div class="grid place-items-center">
                    <div class="w-[60px] h-[60px]  border border-gray rounded-full grid place-items-center">
                        <div class="font-black text-white w-8 h-8 text-center flex justify-center ps-1.5 items-start flex-col space-y-1 rounded-sm bg-orange-500">
                            <span class="w-5 h-1 bg-white rounded-xl"></span>
                            <span class="w-5 h-1 bg-white rounded-xl"></span>
                            <span class="w-2 h-1 bg-white rounded-xl"></span>
                        </div>
                    </div>
                    <p class="mt-2 font-medium capitalize">Lihat History</p>
                </div>
            ', ['/home/view', 'id_user' => Yii::$app->user->identity->id]) ?>
        </div>



    </section>

    <div class="lg:hidden">

        <?= $this->render('@backend/views/components/_footer'); ?>
    </div>


    <footer class="hidden lg:block text-center text-black my-20">
        <p>Copyright &copy; 2024 Profaskes</p>
    </footer>