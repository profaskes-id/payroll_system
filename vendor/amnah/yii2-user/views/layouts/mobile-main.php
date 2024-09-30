<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile</title>
</head>

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


    /* From Uiverse.io by csemszepp */
    .pattern-1 {
        width: 100%;
        height: 100%;
        --s: 100px;
        /* control the size */
        --c1: #1d1d1d;
        --c2: #4e4f51;
        --c3: #3c3c3c;

        background: repeating-conic-gradient(from 30deg,
                #0000 0 120deg,
                var(--c3) 0 180deg) calc(0.5 * var(--s)) calc(0.5 * var(--s) * 0.577),
            repeating-conic-gradient(from 30deg,
                var(--c1) 0 60deg,
                var(--c2) 0 120deg,
                var(--c3) 0 180deg);
        background-size: var(--s) calc(var(--s) * 0.577);
    }

    /* From Uiverse.io by csemszepp */
    .pattern-2 {
        width: 100%;
        height: 100%;
        --s: 104px;
        /* control the size */
        --c1: #f6edb3;
        --c2: #acc4a3;

        --_l: #0000 calc(25% / 3), var(--c1) 0 25%, #0000 0;
        --_g: conic-gradient(from 120deg at 50% 87.5%, var(--c1) 120deg, #0000 0);

        background: var(--_g), var(--_g) 0 calc(var(--s) / 2),
            conic-gradient(from 180deg at 75%, var(--c2) 60deg, #0000 0),
            conic-gradient(from 60deg at 75% 75%, var(--c1) 0 60deg, #0000 0),
            linear-gradient(150deg, var(--_l)) 0 calc(var(--s) / 2),
            conic-gradient(at 25% 25%,
                #0000 50%,
                var(--c2) 0 240deg,
                var(--c1) 0 300deg,
                var(--c2) 0),
            linear-gradient(-150deg, var(--_l)) #55897c
            /* third color here */
        ;
        background-size: calc(0.866 * var(--s)) var(--s);
    }
</style>
<link href="<?= Yii::getAlias('@root') . '/css/tailwind_output.css' ?>" rel="stylesheet">
<link href="<?= Yii::getAlias('@root') . '/node_modules/flowbite/dist/flowbite.min.css' ?>" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<style>
    * {
        font-family: "Poppins", sans-serif;
    }

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


    .costume-container {
        top: -80px;
        position: relative;
        z-index: 1;
        margin-bottom: -50px;
    }
</style>


<body class="w-full mx-auto">


    <!-- alert -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div id="alert-3" class="flex  fixed top-3 left-0 right-0 z-40 w-[90%] mx-auto items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

    <?php elseif (Yii::$app->session->hasFlash('error')): ?>
        <div id="alert-3" class="flex fixed top-3 left-0 right-0 z-40 w-[90%] mx-auto items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    <?php endif;
    ?>
    <!-- Content -->
    <?php echo $content ?>

</body>
<script src="<?= Yii::getAlias('@root') . '/node_modules/flowbite/dist/flowbite.min.js' ?>"></script>

</html>