<section class="">


    <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle=" sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center justify-start hidden p-2 mt-2 text-sm text-gray-500 rounded-lg ms-3 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>

    <aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm-hidden sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
            <div class="flex items-center justify-center w-full h-10 mt-5 mb-10">

                <?php

                use yii\helpers\Html;

                echo Html::img('@root/images/profaskes.png', ['class' => 'w-54 ']) ?>

            </div>
            <hr>
            <ul class="flex flex-col items-start justify-center py-2 space-y-2 font-medium ">
                <li>
                    <a href="/panel/home" class="flex items-center justify-start text-gray-900 rounded-lg group">
                        <div>
                            <div class="p-2 bg-[#1294D2]/10 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                    <path fill="#373737" d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a1 1 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13m7 7v-5h4v5zm2-15.586l6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586z" />
                                </svg>
                            </div>
                        </div>
                        <p class="flex-1 text-left ms-3 rtl:text-right whitespace-nowrap">Beranda</p>
                    </a>
                </li>
                <li>
                    <a href="/panel/home/inbox" class="flex items-center justify-start text-gray-900 rounded-lg group">
                        <div>
                            <div class="p-2 bg-[#1294D2]/10 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="#373737" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14v-3h-3q-.75.95-1.787 1.475T12 18t-2.212-.525T8 16H5zm7-3q.8 0 1.475-.413t1.1-1.087q.15-.225.375-.363t.5-.137H19V5H5v9h3.55q.275 0 .5.138t.375.362q.425.675 1.1 1.088T12 16m-7 3h14z" />
                                </svg>
                            </div>
                        </div>
                        <p class="flex-1 text-left ms-3 rtl:text-right whitespace-nowrap">Inbox</p>
                    </a>
                </li>
                <li>
                    <a href="/panel/home/expirience" class="flex items-center justify-start text-gray-900 rounded-lg group">
                        <div class="p-2 bg-[#39B973]/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24">
                                <path fill="none" stroke="#373737" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7c0 2.21-4.03 4-9 4S3 9.21 3 7m18 0c0-2.21-4.03-4-9-4S3 4.79 3 7m18 0v5M3 7v5m18 0c0 2.21-4.03 4-9 4s-9-1.79-9-4m18 0v5c0 2.21-4.03 4-9 4s-9-1.79-9-4v-5" />
                            </svg>
                        </div>

                        <p class="flex-1 text-left ms-3 rtl:text-right whitespace-nowrap">Data Saya</p>
                    </a>
                </li>

                <li>
                    <a href="/panel/user/account" class="flex items-center justify-start text-gray-900 rounded-lg group">
                        <div class="p-2 bg-[#39B973]/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" viewBox="0 0 24 24">
                                <g fill="none" stroke="#373737" stroke-width="2">
                                    <path stroke-linejoin="round" d="M4 18a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z" />
                                    <circle cx="12" cy="7" r="3" />
                                </g>
                            </svg>
                        </div>

                        <p class="flex-1 text-left ms-3 rtl:text-right whitespace-nowrap">Settings</p>
                    </a>

            </ul>
        </div>
    </aside>

</section>