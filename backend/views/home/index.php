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


    <style type="text/css">
        .add-button {
            border: none;
            display: flex;
            padding: 0.75rem 1.5rem;
            background-color: #488aec;
            color: #ffffff;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            vertical-align: middle;
            align-items: center;
            border-radius: 0.5rem;
            user-select: none;
            gap: 0.65rem;
            box-shadow: 0 4px 6px -1px #488aec31, 0 2px 4px -1px #488aec17;
            transition: all .6s ease;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: "Inter", sans-serif;
        }
    </style>


    <section class="grid grid-cols-10  relative overflow-x-hidden">
        <div class="hidden lg:block lg:col-span-2">
            <?= $this->render('@backend/views/components/_sidebar-desktop');
            ?>
        </div>

        <div class="col-span-12 lg:col-span-8 w-full   px-5 pt-5 ">
            <div class="flex justify-between items-center">
                <div class="flex items-start justify-center flex-col text-2xl">
                    <h1 class="font-medium">Selamat Datang,</h1>
                    <p class="font-extrabold"><?= Yii::$app->user->identity->username ?></p>
                </div>
                <div>
                    <div class="w-[50px] h-[50px] rounded-full bg-[url(https://plus.unsplash.com/premium_photo-1664870883044-0d82e3d63d99?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D)] bg-cover bg-center">
                    </div>
                </div>
            </div>

            <div class=" grid grid-cols-12 place-items-center">
                <div class="col-span-12 lg:col-span-6  w-full">
                    <div class="mt-20">
                        <div class=" flex justify-center align-center">
                            <h1 class="text-3xl md:text-8xl font-bold flex justify-end items-end">13:20<span class=" block text-[20px] lg:text-[35px]">:21</span></h1>
                        </div>
                        <p class="text-center font-medium text-xl lg:text-2xl text-gray-500 mt-2">Kamis, 11 Aug 2024</p>
                    </div>

                    <div class="mt-10 grid place-items-center">
                        <?php $formAbsen = ActiveForm::begin(); ?>

                        <button class="all-none" type="submit">
                            <div class="w-[225px] h-[225px] bg-orange-500 rounded-full lg:rounded-3xl grid place-items-center">
                                <?= Html::img('@root/images/icons/click.svg', ['alt' => 'absen', 'class' => 'w-[70%]] h-[70%] object-cover']) ?>
                            </div>
                        </button>
                        <?php ActiveForm::end(); ?>
                    </div>


                    <div class="mt-10 justify-around hidden lg:flex">
                        <div class="grid place-items-center">
                            <h1>13:20:21</h1>
                            <p>Absen Masuk</p>
                        </div>
                        <div class="grid place-items-center">
                            <h1>8 Jam</h1>
                            <p>Jam Kerja</p>
                        </div>
                    </div>
                </div>


                <!--  -->
                <div class="col-span-12 lg:col-span-6 hidden lg:block  w-full">
                    <div>
                        <div class="bg-rose-500 min-w-[500px] w-full h-[70px] rounded-xl mt-10 mb-5"></div>
                        <?php $form = ActiveForm::begin(); ?>

                        <label>
                            <span>Keterangan</span>
                            <?php echo $form->field($model, 'keterangan')->textarea([
                                "class" => "mt-1 w-full rounded-lg border-gray-200 shadow-sm sm:text-sm border border-gray-500 p-2",
                                "placeholder" => 'Masukan Keterangan',
                                "rows" => 2,
                            ])->label(false) ?>
                        </label>

                        <label>
                            <span>Upload File</span>
                            <?php echo $form->field($model, 'keterangan')->fileInput([
                                "class" => "mt-1 w-full rounded-lg border-gray-200 shadow-sm sm:text-sm border border-gray-500 p-2",
                                "placeholder" => 'Masukan Keterangan',
                                "rows" => 2,
                            ])->label(false) ?>
                        </label>


                        <div class="form-group">
                            <button class="add-button" type="submit">
                                <span>
                                    Submit
                                </span>
                            </button>
                        </div>


                        </label>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="bg-orange-500 min-w-[500px] w-full h-[70px] rounded-xl mt-10"></div>
                    <div class="bg-gray-300 min-w-[500px] w-full h-[70px] rounded-xl mt-10"></div>
                </div>
            </div>
        </div>


        <!-- ? mobile izin -->
        <div class="flex lg:hidden  justify-around my-10 w-screen relative ">
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

    <div class="block lg:hidden  m-5 h-[70px] bg-[#252525] rounded-2xl  grid grid-cols-12 justify-items-center items-center ">
        <div class="col-span-4 grid place-items-center">
            <div class="w-[30px] h-[30px] bg-white rounded-full"></div>
            <p class="text-white">Absen</p>
        </div>
        <div class="col-span-4 grid place-items-center">
            <div class="w-[30px] h-[30px] bg-white rounded-full"></div>
            <p class="text-white">Absen</p>
        </div>
        <div class="col-span-4 grid place-items-center">
            <div class="w-[30px] h-[30px] bg-white rounded-full"></div>
            <p class="text-white">Absen</p>
        </div>

    </div>


    <footer class="hidden lg:block text-center text-black my-20">
        <p>Copyright &copy; 2024 Profaskes</p>
    </footer>