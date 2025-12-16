<style>
    /* MediaPipe Liveness Styles */
    .liveness-container {
        position: relative;
        width: 100%;
        margin-bottom: 1rem;
    }

    .liveness-video-container {
        position: relative;
        width: 100%;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
    }

    .liveness-video {
        width: 100%;
        height: auto;

        display: block;
    }

    .liveness-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .liveness-instruction {
        position: absolute;
        top: 10px;
        left: 0;
        right: 0;
        text-align: center;
        z-index: 10;
    }

    .liveness-status {
        padding: 8px 12px;
        border-radius: 20px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        font-size: 12px;
        display: inline-block;
    }

    .liveness-indicators {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin: 10px 0;
    }

    .liveness-indicator {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }

    .liveness-indicator span {
        font-size: 18px;
    }

    #screenshotResult {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>


<script>
    const MODEL_URL = '<?= Yii::getAlias('@root'); ?>/panel/models';
    // face js
    // Global variable untuk face-api models
    let faceApiModelsLoaded = false;

    // Load face-api.js models
    async function loadFaceApiModels() {
        if (faceApiModelsLoaded) return true;

        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            await faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL);

            faceApiModelsLoaded = true;
            console.log('Face-api.js models loaded successfully');
            return true;
        } catch (error) {
            console.error('Error loading face-api models:', error);
            return false;
        }
    }



    // Function untuk extract face descriptor
    async function extractFaceDescriptor(canvasElement, modalId) {
        document.getElementById('submitButton').disabled = true;
        const statusText = document.getElementById(`status-${modalId}`);

        try {
            // Load models jika belum
            const modelsLoaded = await loadFaceApiModels();
            if (!modelsLoaded) {
                statusText.textContent = '⚠ Gagal memuat model deteksi wajah.';
                return;
            }

            // Detect face
            const detection = await faceapi
                .detectSingleFace(
                    canvasElement,
                    new faceapi.TinyFaceDetectorOptions({
                        inputSize: 416,
                        scoreThreshold: 0.5
                    })
                )
                .withFaceLandmarks()
                .withFaceDescriptor();
            if (!detection) {
                statusText.textContent = '⚠ Wajah tidak terdeteksi. Coba lagi.';
                // Reset untuk mengambil ulang
                setTimeout(() => {
                    statusText.textContent = 'Silakan coba lagi...';
                    resetLiveness(modalId);
                }, 2000);
                return;
            }

            // Get face descriptor (array of 128 numbers)
            const descriptor = detection.descriptor;


            // Convert descriptor array to JSON string
            const descriptorJson = descriptor;

            document.getElementById('faceDescriptor').value = descriptorJson;
            document.getElementById('submitButton').disabled = false;


            statusText.textContent = '✅ Wajah terdeteksi! Klik "Simpan Absen" untuk melanjutkan.';


            return descriptor;

        } catch (error) {
            console.error('Error extracting face descriptor:', error);
            statusText.textContent = '⚠ Error saat mendeteksi wajah. Coba lagi.';
            return null;
        }
    }


    // media pipe

    // Global variables for each modal
    const livenessState = {};

    // Initialize liveness state for a modal
    function initLivenessState(modalId) {
        livenessState[modalId] = {
            faceLandmarker: null,
            video: null,
            canvas: null,
            canvasCtx: null,
            runningMode: "IMAGE",
            webcamRunning: false,
            blinkVerified: false,
            mouthVerified: false,
            autoCaptured: false,
            stream: null
        };
    }

    // Import MediaPipe
    async function loadMediaPipe() {
        return import("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.3");
    }

    // Initialize Face Landmarker for a modal
    async function initFaceLandmarker(modalId) {
        const vision = await loadMediaPipe();
        const {
            FaceLandmarker,
            FilesetResolver
        } = vision;

        const filesetResolver = await FilesetResolver.forVisionTasks(
            "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.3/wasm"
        );

        livenessState[modalId].faceLandmarker = await FaceLandmarker.createFromOptions(filesetResolver, {
            baseOptions: {
                modelAssetPath: "https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task",
                delegate: "GPU"
            },
            outputFaceBlendshapes: true,
            runningMode: livenessState[modalId].runningMode,
            numFaces: 1
        });

        console.log(`Face Landmarker initialized for ${modalId}`);
    }

    // Start liveness verification
    async function startLivenessVerification(modalId) {
        if (!livenessState[modalId]) initLivenessState(modalId);

        // Get elements
        const video = document.getElementById(`webcam-${modalId}`);
        const canvas = document.getElementById(`output_canvas-${modalId}`);
        const instructionOverlay = document.getElementById(`instruction-overlay-${modalId}`);
        const startBtn = document.getElementById(`startLivenessBtn-${modalId}`);
        const stopBtn = document.getElementById(`stopLivenessBtn-${modalId}`);
        const indicators = document.getElementById(`indicators-${modalId}`);
        const statusText = document.getElementById(`status-${modalId}`);

        // Initialize Face Landmarker if not done
        if (!livenessState[modalId].faceLandmarker) {
            await initFaceLandmarker(modalId);
        }

        // Request webcam access
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: {
                        ideal: 640
                    },
                    height: {
                        ideal: 480
                    },
                    facingMode: "user"
                }
            });

            livenessState[modalId].stream = stream;
            video.srcObject = stream;
            livenessState[modalId].webcamRunning = true;

            // Initialize canvas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            livenessState[modalId].canvasCtx = canvas.getContext('2d');

            // Update UI
            instructionOverlay.innerHTML = '<div class="liveness-status">Silakan hadapkan wajah ke kamera</div>';
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
            indicators.style.display = 'flex';
            statusText.textContent = 'Verifikasi dimulai...';

            // Reset verification state
            livenessState[modalId].blinkVerified = false;
            livenessState[modalId].mouthVerified = false;
            livenessState[modalId].autoCaptured = false;

            // Update indicators
            updateIndicator(modalId, 'blink', false);
            updateIndicator(modalId, 'mouth', false);

            // Start prediction loop
            predictWebcam(modalId);

        } catch (error) {
            console.error('Error accessing webcam:', error);
            statusText.textContent = 'Gagal mengakses kamera. Pastikan izin kamera diaktifkan.';
            statusText.style.color = 'red';
        }
    }

    // Stop liveness verification
    function stopLivenessVerification(modalId) {
        const state = livenessState[modalId];
        if (!state) return;

        state.webcamRunning = false;

        // Stop webcam stream
        if (state.stream) {
            state.stream.getTracks().forEach(track => track.stop());
            state.stream = null;
        }

        // Update UI
        const video = document.getElementById(`webcam-${modalId}`);
        const startBtn = document.getElementById(`startLivenessBtn-${modalId}`);
        const stopBtn = document.getElementById(`stopLivenessBtn-${modalId}`);
        const instructionOverlay = document.getElementById(`instruction-overlay-${modalId}`);

        if (video) video.srcObject = null;
        if (startBtn) startBtn.style.display = 'inline-block';
        if (stopBtn) stopBtn.style.display = 'none';
        if (instructionOverlay) {
            instructionOverlay.innerHTML = '<div class="liveness-status">Verifikasi dihentikan</div>';
        }
    }

    // Update indicator status
    function updateIndicator(modalId, type, verified) {
        const indicator = document.getElementById(`${type}-indicator-${modalId}`);
        if (!indicator) return;

        if (verified) {
            indicator.innerHTML = `<span>✅</span><span>${type === 'blink' ? 'Berkedip' : 'Buka Mulut'}</span>`;
            indicator.style.color = 'green';
        } else {
            indicator.innerHTML = `<span>⏳</span><span>${type === 'blink' ? 'Berkedip' : 'Buka Mulut'}</span>`;
            indicator.style.color = 'gray';
        }
    }

    // Webcam prediction loop
    async function predictWebcam(modalId) {
        const state = livenessState[modalId];
        if (!state || !state.webcamRunning || !state.faceLandmarker) return;

        const video = document.getElementById(`webcam-${modalId}`);
        const statusText = document.getElementById(`status-${modalId}`);

        if (!video || video.readyState !== 4) {
            requestAnimationFrame(() => predictWebcam(modalId));
            return;
        }

        // Switch to VIDEO mode if needed
        if (state.runningMode === "IMAGE") {
            state.runningMode = "VIDEO";
            await state.faceLandmarker.setOptions({
                runningMode: state.runningMode
            });
        }

        // Detect face landmarks
        const results = state.faceLandmarker.detectForVideo(video, performance.now());

        // Process results
        if (results.faceBlendshapes && results.faceBlendshapes.length > 0) {
            const blend = results.faceBlendshapes[0].categories;
            const jaw = blend.find(s => s.categoryName === "jawOpen")?.score || 0;
            const blinkL = blend.find(s => s.categoryName === "eyeBlinkLeft")?.score || 0;
            const blinkR = blend.find(s => s.categoryName === "eyeBlinkRight")?.score || 0;

            // Check for blink
            if (!state.blinkVerified && blinkL > 0.5 && blinkR > 0.5) {
                state.blinkVerified = true;
                updateIndicator(modalId, 'blink', true);
                statusText.textContent = '✅ Kedip terdeteksi! Sekarang buka mulut Anda...';
                statusText.style.color = 'green';
            }

            // Check for mouth open
            if (state.blinkVerified && !state.mouthVerified && jaw > 0.35) {
                state.mouthVerified = true;
                updateIndicator(modalId, 'mouth', true);
                statusText.textContent = '✅ Mulut terbuka terdeteksi! Mengambil foto...';
                statusText.style.color = 'green';

                // Capture photo after a delay
                await startCountdown(modalId);;
            }
        }

        // Continue loop
        if (state.webcamRunning) {
            requestAnimationFrame(() => predictWebcam(modalId));
        }
    }

    async function capturePhoto(modalId) {
        // tampilkan process indicator
        document.getElementById('process-indicator').classList.remove('hidden');

        // Base64 mulai
        setStatus('status-base64', 'loading');
        await delay(300);
        setStatus('status-base64', 'success');

        // Descriptor mulai
        setStatus('status-descriptor', 'loading');
        await delay(300);

        const state = livenessState[modalId];
        if (!state) return;

        const video = document.getElementById(`webcam-${modalId}`);
        const canvas = document.getElementById(`output_canvas-${modalId}`);
        const resultsContainer = document.getElementById(`results-${modalId}`);
        const screenshotImg = document.getElementById(`screenshotResult-${modalId}`);
        const faceDataInput = document.getElementById(`faceData`);
        const statusText = document.getElementById(`status-${modalId}`);

        if (!video || !canvas) return;

        // Buat canvas baru untuk capture (tidak mirror)
        const captureCanvas = document.createElement('canvas');
        const captureCtx = captureCanvas.getContext('2d');

        // Set canvas size sesuai video
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;

        // **TANPA MIRROR** - Gambar seperti asli webcam
        captureCtx.drawImage(
            video,
            0,
            0,
            captureCanvas.width,
            captureCanvas.height
        );

        // Get data URL (base64) dari canvas yang TIDAK di-mirror
        const dataURL = captureCanvas.toDataURL('image/jpeg', 0.8);

        // Preview (TIDAK dikompres)
        screenshotImg.src = dataURL;

        // Kompres hanya untuk input
        compressBase64Only(dataURL, 0.6, 800).then(compressed => {
            faceDataInput.value = compressed;
        });

        resultsContainer.classList.remove('hidden');


        // Sembunyikan live view
        $("#liveness-container-popup-modal").hide();

        statusText.textContent = '✅ Foto berhasil diambil! Mendeteksi wajah...';
        state.autoCaptured = true;

        // Extract face descriptor menggunakan face-api.js
        await delay(100); // kasih waktu DOM update
        const descriptor = await extractFaceDescriptor(captureCanvas, modalId);

        if (descriptor) {
            setStatus('status-descriptor', 'success');
        } else {
            setStatus('status-descriptor', 'error');
        }


        // Stop webcam
        stopLivenessVerification(modalId);

        // Hapus canvas temporary
        captureCanvas.remove();
    }


    function compressBase64Only(base64, quality = 0.6, maxWidth = 800) {
        return new Promise(resolve => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height = height * (maxWidth / width);
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;

                ctx.drawImage(img, 0, 0, width, height);

                resolve(canvas.toDataURL('image/jpeg', quality));
            };
            img.src = base64;
        });
    }


    // Update resetLiveness function
    function resetLiveness(modalId) {
        // Stop current verification
        stopLivenessVerification(modalId);

        // Reset UI
        const resultsContainer = document.getElementById(`results-${modalId}`);
        const indicators = document.getElementById(`indicators-${modalId}`);
        const statusText = document.getElementById(`status-${modalId}`);
        const instructionOverlay = document.getElementById(`instruction-overlay-${modalId}`);

        if (resultsContainer) resultsContainer.classList.add('hidden');
        if (indicators) indicators.style.display = 'none';
        if (statusText) {
            statusText.textContent = '';
            statusText.style.color = '';
        }
        if (instructionOverlay) {
            instructionOverlay.innerHTML = '<div class="liveness-status">Tekan "Mulai Verifikasi"</div>';
        }

        // Clear form inputs
        document.getElementById('faceData').value = '';
        document.getElementById('faceDescriptor').value = '';

        // Reset state
        if (livenessState[modalId]) {
            livenessState[modalId].blinkVerified = false;
            livenessState[modalId].mouthVerified = false;
            livenessState[modalId].autoCaptured = false;
        }
    }
    // Close modal handler
    function closeModalFace(modalId) {
        stopLivenessVerification(modalId);
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementsByClassName('bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40')[0].remove();
        // resetLiveness(modalId);
    }

    // Initialize when modal opens
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize liveness state for popup-modal
        initLivenessState('popup-modal');

        // Handle modal show event
        const modal = document.getElementById('popup-modal');
        const modalButton = document.querySelector('[data-modal-target="popup-modal"]');

        if (modalButton) {
            modalButton.addEventListener('click', function() {
                modal.classList.remove('hidden');
                resetLiveness('popup-modal');
            });
        }

        // Handle form submission

    });



    function showProcessIndicator() {
        const box = document.getElementById('process-indicator');
        box.classList.remove('hidden');
        setStatus('status-base64', 'loading');
        setStatus('status-descriptor', 'loading');
    }


    function setStatus(id, status) {
        const el = document.querySelector(`#${id} span:first-child`);

        if (status === 'loading') el.textContent = '⏳';
        if (status === 'success') el.textContent = '✅';
        if (status === 'error') el.textContent = '❌';
    }

    async function startCountdown(modalId) {
        const statusText = document.getElementById(`status-${modalId}`);

        for (let i = 3; i > 0; i--) {
            statusText.innerHTML = `
            <span class="text-xl font-bold text-yellow-500">
                📸 Foto diambil dalam ${i}
            </span>
        `;
            await delay(1000);
        }

        statusText.innerHTML = `
        <span class="font-semibold text-green-600">
            📸 Mengambil foto...
        </span>
    `;

        await delay(500);
        await capturePhoto(modalId);
    }

    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
</script>