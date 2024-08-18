<div class="mt-20">
    <div class=" flex justify-center align-center">
        <h1 id="clock" class="text-6xl md:text-8xl font-bold flex justify-end items-end">
            <span id="hours">00</span>:<span id="minutes">00</span><span class="text-[22px] lg:text-[50px]">:</span><span class="text-[22px] lg:text-[50px]" id="seconds">00</span>
        </h1>
    </div>
    <p id="date" class="text-center font-medium text-xl lg:text-2xl text-gray-500 mt-2"></p>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        document.getElementById('hours').textContent = hours;
        document.getElementById('minutes').textContent = minutes;
        document.getElementById('seconds').textContent = seconds;
    }

    setInterval(updateClock, 1000);
    updateClock(); // Call it once to avoid delay


    function updateDate() {
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayName = days[now.getDay()];
        const date = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();

        document.getElementById('date').textContent = `${dayName}, ${date} ${monthName} ${year}`;
    }
    updateDate();
</script>