<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajak Absen Payroll System</title>

    <link href="<?= Yii::getAlias('@root') . '/css/tailwind_output.css' ?>" rel="stylesheet">
    <link href="<?= Yii::getAlias('@root') . '/node_modules/flowbite/dist/flowbite.min.css' ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        * {
            font-family: "Poppins", sans-serif;
        }

        /* Improved Alert Styling */
        .alert-container {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 2rem);
            max-width: 600px;
            z-index: 9999;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        .alert-hidden {
            animation: slideUp 0.3s ease-out;
            opacity: 0;
            transform: translate(-50%, -20px);
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translate(-50%, 0);
            }

            to {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
        }

        /* Button Styles */
        .tw-add {
            border: none;
            display: flex;
            padding-block: 0.75rem;

            color: #ffffff;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-transform: uppercase;
            vertical-align: middle;
            align-items: center;
            border-radius: 0.5rem;
            user-select: none;
            gap: 0.75rem;
            box-shadow: 0 4px 6px -1px #488aec31, 0 2px 4px -1px #488aec17;
            transition: all 0.6s ease;
        }

        .tw-add :hover {
            box-shadow: 0 10px 15px -3px #488aec4f, 0 4px 6px -2px #488aec17;
        }

        .tw-add :focus,
        .tw-add :active {
            opacity: 0.85;
            box-shadow: none;
        }

        .tw-add svg {
            width: 1.25rem;
            height: 1.25rem;
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


        .costume-btn {
            width: 130px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #131133;
            border: none;
            color: white;
            font-weight: 600;
            gap: 8px;
            cursor: pointer;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.103);
            position: relative;
            overflow: hidden;
            transition-duration: .3s;
            margin-left: auto;
        }

        .svgIcon {
            width: 16px;
        }

        .svgIcon path {
            fill: white;
        }

        .costume-btn::before {
            width: 130px;
            height: 130px;
            position: absolute;
            content: "";
            background-color: #ffffff;

            border-radius: 50%;
            left: -100%;
            top: 0;
            transition-duration: .3s;
            mix-blend-mode: difference;
        }

        .costume-btn:hover::before {
            transition-duration: .3s;
            transform: translate(100%, -50%);
            border-radius: 0;
        }

        .costume-btn:active {
            transform: translate(5px, 5px);
            transition-duration: .3s;
        }

        .add-button:hover {
            box-shadow: 0 10px 15px -3px #488aec4f, 0 4px 6px -2px #488aec17;
        }

        /* Mobile Footer */
        .mobile-footer {
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="relative w-full mx-auto bg-gray-50">
    <section class="grid min-h-screen grid-cols-11">
        <!-- Mobile Footer (hidden on desktop) -->
        <?php
        // More robust condition checking for footer display
        $shouldHideFooter = false;
        $currentAction = $this->context->action->id ?? '';

        if (is_string($currentAction)) {
            $shouldHideFooter = str_contains($currentAction, 'create') || str_contains($currentAction, 'update');
        }

        if (!$shouldHideFooter): ?>
            <div class="xl:hidden fixed bottom-0 left-0 right-0 w-full z-[50] bg-white shadow-lg border-t border-gray-200">
                <?= $this->render('@backend/views/components/_footer'); ?>
            </div>
        <?php endif; ?>

        <!-- Desktop Sidebar -->
        <div class="col-span-11 xl:col-span-2 z-[20]  hidden md:block ">
            <div class="fixed bottom-0 left-0 right-0 hidden w-full h-screen xl:block">
                <?= $this->render('@backend/views/components/_sidebar-desktop'); ?>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-span-12 pb-20 xl:col-span-9 xl:pb-0">
            <!-- Alert Container (centered and properly sized) -->
            <?php if (Yii::$app->session->hasFlash('success') || Yii::$app->session->hasFlash('error')): ?>
                <div class="alert-container">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50" role="alert">
                            <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <div class="ml-3 text-sm font-medium">
                                <?= Yii::$app->session->getFlash('success') ?>
                            </div>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-success" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    <?php elseif (Yii::$app->session->hasFlash('error')): ?>
                        <div id="alert-error" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50" role="alert">
                            <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <div class="ml-3 text-sm font-medium">
                                <?= Yii::$app->session->getFlash('error') ?>
                            </div>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-error" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="p-4 xl:p-6 z-[99]">
                <?php echo $content ?>
            </div>
        </div>
    </section>

    <script src="<?= Yii::getAlias('@root') . '/node_modules/flowbite/dist/flowbite.min.js' ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Improved alert dismissal with animation
        document.querySelectorAll('[data-dismiss-target]').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-dismiss-target'));
                if (target) {
                    target.classList.add('alert-hidden');
                    setTimeout(() => {
                        target.remove();
                    }, 300);
                }
            });
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-container > div').forEach(alert => {
                alert.classList.add('alert-hidden');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>

</html>