<div class="flex flex-col justify-between w-64 h-screen border-gray-100 bg-slate-900 border-e">
    <div class="px-4 py-6">
        <div class="flex items-center pb-3 mt-3 mb-3 user-panel">
            <div class="image">
                <img src="<?= Yii::getAlias('@root') ?>/images/logo.svg" alt="Profaskes Logo" class="brand-image img-circle " style="width: 60px; ">
            </div>
            <div class="flex flex-col justify-center ms-2">
                <a href="#" style="font-size: 17.8px;" class="block font-bold text-white">Profaskes</a>
                <a href="#" style="font-size:14px" class="block text-white">Payroll System</a>
            </div>
        </div>

        <ul class="mt-6 space-y-1">
            <li>
                <a
                    href="/panel/home"
                    class="block px-4 py-2 text-sm font-medium text-white ">
                    Beranda
                </a>
            </li>

            <li>
                <a
                    href="/panel/home/inbox"
                    class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                    Inbox
                </a>
            </li>

            <li>
                <a
                    href="/panel/home/expirience"
                    class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                    Data Saya
                </a>
            </li>


            <li>
                <details class="group [&_summary::-webkit-details-marker]:hidden">
                    <summary
                        class="flex items-center justify-between px-4 py-2 text-white rounded-lg cursor-pointer hover:bg-gray-100 hover:text-gray-700">
                        <span class="text-sm font-medium"> Pengajuan </span>

                        <span class="transition duration-300 shrink-0 group-open:-rotate-180">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="size-5"
                                viewBox="0 0 20 20"
                                fill="white">
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </summary>

                    <ul class="px-4 mt-2 space-y-1">
                        <li>
                            <a
                                href="/panel/tanggapan/wfh"
                                class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                                WFH
                            </a>
                        </li>

                        <li>
                            <a
                                href="/panel/tanggapan/lembur"
                                class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                                Lembur
                            </a>
                        </li>
                        <li>
                            <a
                                href="/panel/tanggapan/cuti"
                                class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                                Cuti
                            </a>
                        </li>
                        <li>
                            <a
                                href="/panel/tanggapan/dinas"
                                class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                                Dinas
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <a
                    href="/panel/user/account"

                    class="block px-4 py-2 text-sm font-medium text-white rounded-lg hover:bg-gray-100 hover:text-gray-700">
                    Account
                </a>
            </li>


        </ul>
    </div>


</div>