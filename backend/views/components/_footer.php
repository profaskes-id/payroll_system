<?php

use yii\helpers\Html;

if (Yii::$app->request->getPathInfo() ==  "user/account"): ?>
    <link href="<?= Yii::getAlias('@root') . '/css/tailwind_output.css' ?>" rel="stylesheet">
<?php endif ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<style>
    * {
        font-family: "Poppins", sans-serif;
    }
</style>




<div style="z-index: 999  !important;  background: #fff; " class=" z-[999] relative rounded-full -translate-y-4 h-16 w-[90%] md:w-[50%] -translate-x-1/2 bg-white border  bottom-0 left-1/2 dark:bg-gray-700 dark:border-gray-600 ">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto">
        <a href="/panel/home" data-tooltip-target="tooltip-home" class="inline-flex flex-col items-center justify-center px-5 rounded-s-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 16 16">
                <g fill="none">
                    <path fill="url(#SVGkm4eqcHj)" d="M6 9h4v5H6z" />
                    <path fill="url(#SVG4sqiUdhg)" d="M8.687 2.273a1 1 0 0 0-1.374 0l-4.844 4.58A1.5 1.5 0 0 0 2 7.943v4.569a1.5 1.5 0 0 0 1.5 1.5h3v-4a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v4h3a1.5 1.5 0 0 0 1.5-1.5v-4.57a1.5 1.5 0 0 0-.47-1.09z" />
                    <path fill="url(#SVGbhYexcuE)" fill-rule="evenodd" d="m8.004 2.636l5.731 5.41a.75.75 0 1 0 1.03-1.091L8.86 1.382a1.25 1.25 0 0 0-1.724.007L1.23 7.059a.75.75 0 0 0 1.038 1.082z" clip-rule="evenodd" />
                    <defs>
                        <linearGradient id="SVGkm4eqcHj" x1="8" x2="4.796" y1="9" y2="14.698" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#944600" />
                            <stop offset="1" stop-color="#cd8e02" />
                        </linearGradient>
                        <linearGradient id="SVG4sqiUdhg" x1="3.145" x2="14.93" y1="1.413" y2="10.981" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#ffd394" />
                            <stop offset="1" stop-color="#ffb357" />
                        </linearGradient>
                        <linearGradient id="SVGbhYexcuE" x1="10.262" x2="6.945" y1="-.696" y2="7.895" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#ff921f" />
                            <stop offset="1" stop-color="#eb4824" />
                        </linearGradient>
                    </defs>
                </g>
            </svg>
            <span class="sr-only">Home</span>
        </a>
        <div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Home
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <a href="/panel/home/expirience" data-tooltip-target="tooltip-wallet" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">

            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32">
                <g fill="none">
                    <path fill="url(#SVGmfoCG4sx)" d="M6 25h20.688S26 25.4 26 27s.688 2 .688 2H9a3 3 0 0 1-3-3z" />
                    <path fill="url(#SVGTziDsdLM)" d="M9 28a2 2 0 0 1-2-2h18.25A1.75 1.75 0 0 0 27 24.25V6a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v20a4 4 0 0 0 4 4h17a1 1 0 1 0 0-2z" />
                    <path fill="url(#SVGFwOhUjyV)" d="M10.75 6A1.75 1.75 0 0 0 9 7.75v2.5c0 .966.784 1.75 1.75 1.75h10.5A1.75 1.75 0 0 0 23 10.25v-2.5A1.75 1.75 0 0 0 21.25 6z" />
                    <path fill="url(#SVGDCVzEdpO)" fill-opacity="0.3" d="M27 6a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v20a4 4 0 0 0 4 4h17a1 1 0 0 0 1-1z" />
                    <path fill="url(#SVGBtRNlbpr)" fill-opacity="0.3" d="M27 6a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v20a4 4 0 0 0 4 4h17a1 1 0 0 0 1-1z" />
                    <path fill="url(#SVGC3MIQc4V)" d="M17 28c0 .546.34 1.059.936 1.5c1.21.897 3.473 1.5 6.064 1.5c3.866 0 7-1.343 7-3v-9c-.436.33-.936.603-1.46.827l-.04.018c-.6.253-1.274.462-2 .621a16.5 16.5 0 0 1-3.5.361c-2.086 0-4.046-.36-5.54-1A7 7 0 0 1 17 19z" />
                    <path fill="url(#SVGmNXiSOJZ)" d="M27.5 21.599c.772-.192 1.45-.445 2-.743c.94-.51 1.5-1.155 1.5-1.856c0-.7-.56-1.345-1.5-1.856c-.55-.298-1.228-.551-2-.743A14.7 14.7 0 0 0 24 16c-3.866 0-7 1.343-7 3s3.134 3 7 3c1.275 0 2.47-.146 3.5-.401" />
                    <defs>
                        <linearGradient id="SVGmfoCG4sx" x1="15.565" x2="15.565" y1="28.2" y2="25" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#9deaff" />
                            <stop offset=".716" stop-color="#58aafe" />
                        </linearGradient>
                        <linearGradient id="SVGTziDsdLM" x1="12.591" x2="16.974" y1="7.239" y2="37.373" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#20ac9d" />
                            <stop offset="1" stop-color="#2052cb" />
                        </linearGradient>
                        <linearGradient id="SVGFwOhUjyV" x1="13.828" x2="20.922" y1="4.971" y2="15.271" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#9ff0f9" />
                            <stop offset="1" stop-color="#6ce0ff" />
                        </linearGradient>
                        <linearGradient id="SVGC3MIQc4V" x1="20.305" x2="28.471" y1="16.297" y2="29.692" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#3bd5ff" />
                            <stop offset="1" stop-color="#4894fe" />
                        </linearGradient>
                        <linearGradient id="SVGmNXiSOJZ" x1="28.667" x2="20.111" y1="22.6" y2="16" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#66c0ff" />
                            <stop offset="1" stop-color="#c8f3ff" />
                        </linearGradient>
                        <radialGradient id="SVGDCVzEdpO" cx="0" cy="0" r="1" gradientTransform="matrix(-6.66666 3.75003 -4.0889 -7.26909 19 28)" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#4a43cb" />
                            <stop offset=".443" stop-color="#4a43cb" stop-opacity="0" />
                        </radialGradient>
                        <radialGradient id="SVGBtRNlbpr" cx="0" cy="0" r="1" gradientTransform="matrix(0 -11.5 5.96562 0 20.5 27)" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#171155" />
                            <stop offset=".328" stop-color="#171155" />
                            <stop offset="1" stop-color="#4a43cb" stop-opacity="0" />
                        </radialGradient>
                    </defs>
                </g>
            </svg>
            <span class="sr-only">Data Saya</span>
        </a>
        <div id="tooltip-wallet" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Data Saya
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <div class="flex items-center justify-center">
            <a href="/panel/home/absen-masuk" data-tooltip-target="tooltip-new" class="relative inline-flex items-center justify-center w-12 h-12 font-medium -translate-y-5 ">
                <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 20 20">
                    <g fill="none">
                        <path fill="url(#SVGzyQtsdle)" d="M11.092 1.29a.75.75 0 0 0-1.184 0L8.844 2.658a.5.5 0 0 1-.583.157L6.657 2.16a.75.75 0 0 0-1.025.592L5.395 4.47a.5.5 0 0 1-.427.427l-1.716.237a.75.75 0 0 0-.592 1.025l.653 1.604a.5.5 0 0 1-.157.583L1.79 9.408a.75.75 0 0 0 0 1.185l1.366 1.063a.5.5 0 0 1 .157.583l-.653 1.604a.75.75 0 0 0 .592 1.025l1.716.237a.5.5 0 0 1 .427.427l.237 1.716a.75.75 0 0 0 1.025.592l1.604-.653a.5.5 0 0 1 .583.157l1.064 1.366a.75.75 0 0 0 1.184 0l1.063-1.366a.5.5 0 0 1 .583-.157l1.604.653a.75.75 0 0 0 1.025-.592l.237-1.716a.5.5 0 0 1 .427-.427l1.716-.237a.75.75 0 0 0 .592-1.025l-.653-1.604a.5.5 0 0 1 .157-.583l1.366-1.064a.75.75 0 0 0 0-1.184l-1.366-1.063a.5.5 0 0 1-.157-.583l.653-1.604a.75.75 0 0 0-.592-1.025l-1.716-.237a.5.5 0 0 1-.426-.427l-.238-1.716a.75.75 0 0 0-1.025-.592l-1.604.653a.5.5 0 0 1-.583-.157z" />
                        <path fill="url(#SVGOlrhfb5g)" fill-opacity="0.95" d="M6.5 10a.5.5 0 0 1 .5-.5h3v-3a.5.5 0 0 1 1 0v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3H7a.5.5 0 0 1-.5-.5" />
                        <defs>
                            <radialGradient id="SVGzyQtsdle" cx="0" cy="0" r="1" gradientTransform="matrix(-19.58616 -34.63252 33.172 -18.76018 22.147 21.623)" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#ffc470" />
                                <stop offset=".251" stop-color="#ff835c" />
                                <stop offset=".55" stop-color="#f24a9d" />
                                <stop offset=".814" stop-color="#b339f0" />
                            </radialGradient>
                            <linearGradient id="SVGOlrhfb5g" x1="13.944" x2="5.55" y1="16.259" y2="10.821" gradientUnits="userSpaceOnUse">
                                <stop offset=".024" stop-color="#ffc8d7" />
                                <stop offset=".807" stop-color="#fff" />
                            </linearGradient>
                        </defs>
                    </g>
                </svg>
                <span class="sr-only">Absensi</span>
            </a>
        </div>
        <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Absensi
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <a href="/panel/home/your-location" data-tooltip-target="tooltip-settings" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg xmlns="http://www.w3.org/2000/svg" width="2.2em" height="2.2em" viewBox="0 0 16 16">
                <g fill="none">
                    <path fill="url(#SVGyspx1cjV)" d="M14 12.5C14 14 11.314 15 8 15s-6-1-6-2.5S4.686 10 8 10s6 1 6 2.5" />
                    <path fill="url(#SVGLx4gDddK)" d="M8 1a5 5 0 0 0-5 5c0 1.144.65 2.35 1.393 3.372c.757 1.043 1.677 1.986 2.346 2.62a1.824 1.824 0 0 0 2.522 0c.669-.634 1.589-1.577 2.346-2.62C12.349 8.35 13 7.144 13 6a5 5 0 0 0-5-5" />
                    <path fill="url(#SVGd9SP9cVI)" d="M9.5 6a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0" />
                    <defs>
                        <linearGradient id="SVGLx4gDddK" x1=".813" x2="8.969" y1="-2.285" y2="10.735" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#f97dbd" />
                            <stop offset="1" stop-color="#d7257d" />
                        </linearGradient>
                        <linearGradient id="SVGd9SP9cVI" x1="6.674" x2="8.236" y1="6.133" y2="7.757" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#fdfdfd" />
                            <stop offset="1" stop-color="#fecbe6" />
                        </linearGradient>
                        <radialGradient id="SVGyspx1cjV" cx="0" cy="0" r="1" gradientTransform="matrix(9.42857 -1.66667 .69566 3.93547 7.571 11.667)" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#7b7bff" />
                            <stop offset=".502" stop-color="#a3a3ff" />
                            <stop offset="1" stop-color="#ceb0ff" />
                        </radialGradient>
                    </defs>
                </g>
            </svg>
            <span class="sr-only">Lokasi </span>
        </a>
        <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Lokasi
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <a href="/panel/user/account" data-tooltip-target="tooltip-profile" type="button" class="inline-flex flex-col items-center justify-center px-5 rounded-e-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24">
                <g fill="none">
                    <path fill="url(#SVG6YOBWcVG)" d="M12.012 2.25c.734.008 1.465.093 2.182.253a.75.75 0 0 1 .582.649l.17 1.527a1.384 1.384 0 0 0 1.927 1.116l1.4-.615a.75.75 0 0 1 .85.174a9.8 9.8 0 0 1 2.205 3.792a.75.75 0 0 1-.272.825l-1.241.916a1.38 1.38 0 0 0 0 2.226l1.243.915a.75.75 0 0 1 .272.826a9.8 9.8 0 0 1-2.204 3.792a.75.75 0 0 1-.849.175l-1.406-.617a1.38 1.38 0 0 0-1.926 1.114l-.17 1.526a.75.75 0 0 1-.571.647a9.5 9.5 0 0 1-4.406 0a.75.75 0 0 1-.572-.647l-.169-1.524a1.382 1.382 0 0 0-1.925-1.11l-1.406.616a.75.75 0 0 1-.85-.175a9.8 9.8 0 0 1-2.203-3.796a.75.75 0 0 1 .272-.826l1.243-.916a1.38 1.38 0 0 0 0-2.226l-1.243-.914a.75.75 0 0 1-.272-.826a9.8 9.8 0 0 1 2.205-3.792a.75.75 0 0 1 .85-.174l1.4.615a1.387 1.387 0 0 0 1.93-1.118l.17-1.526a.75.75 0 0 1 .583-.65q1.074-.238 2.201-.252M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6" />
                    <defs>
                        <linearGradient id="SVG6YOBWcVG" x1="16.682" x2="5.785" y1="20.995" y2="3.996" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#70777d" />
                            <stop offset="1" stop-color="#b9c0c7" />
                        </linearGradient>
                    </defs>
                </g>
            </svg>
            <span class="sr-only">Setting</span>
        </a>
        <div id="tooltip-profile" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Setting
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    </div>
</div>