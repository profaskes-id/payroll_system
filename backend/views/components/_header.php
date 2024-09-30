<div class="grid grid-cols-12  items-center ">

    <div class="  mt-2 col-span-2">
        <a href="<?= $link ?? '/panel/home' ?>">
            <button class=" text-white font-medium rounded-lg text-sm py-2.5 mb-2" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                    <path fill="#272727" d="M10.78 19.03a.75.75 0 0 1-1.06 0l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.749.749 0 0 1 1.275.326a.75.75 0 0 1-.215.734L5.81 11.5h14.44a.75.75 0 0 1 0 1.5H5.81l4.97 4.97a.75.75 0 0 1 0 1.06" />
                </svg>
            </button>
        </a>
    </div>


    <div class="col-span-8 grid place-items-center">
        <h2 class="text-center font-semibold capitalize "><?= $title ?></h2>
    </div>


    <div class="col-span-2">&nbsp;</div>

</div>