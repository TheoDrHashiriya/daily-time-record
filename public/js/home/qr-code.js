const qrCodeModal = document.getElementById("login-qr-code");

const qrCodeCloseButtons = qrCodeModal.querySelectorAll(".close-button");
qrCodeCloseButtons.forEach(button => {
	button.addEventListener("click", closeModal);
});

let stream;
let cameraPromise;
let cameraActive = false;
let video = qrCodeModal.querySelector("video");

async function initCamera() {
	if (stream || cameraActive) return;
	cameraActive = true;

	try {
		cameraPromise = navigator.mediaDevices.getUserMedia({
			video: { facingMode: "environment", width: 640, height: 640 }
		});
		stream = await cameraPromise;

		if (!cameraActive) {
			stream.getTracks().forEach(track => track.stop());
			stream = null;
			return;
		}

		video.srcObject = stream;
		await video.play();

		while (video.videoWidth === 0 && cameraActive)
			await new Promise(r => requestAnimationFrame(r));

		if (cameraActive)
			startScanning(video);

	} catch (err) {
		console.error("Camera init failed:", err);
	} finally {
		cameraPromise = null;
	}
}

function showLoginQrCodeModal() {
	initCamera();
	qrCodeModal.classList.add("show");
}

async function closeModal() {
	cameraActive = false
	
	try {
		if (stream) {
			stream.getTracks().forEach(track => track.stop());
			stream = null;
		} else if (cameraPromise) {
			const s = await cameraPromise;
			s.getTracks()
				.forEach(track => track.stop());
			stream = null;
			cameraPromise = null;
		}
	} catch (err) {
		console.log("No stream detected.", err);
	}

	qrCodeModal.classList.remove("show");
	lastScannedCode = null;
	fetch("logout", { method: "POST" });
}

let lastScannedCode = null;
let scanCooldown = false;

function startScanning(video) {
	const canvas = document.createElement("canvas");
	const context = canvas.getContext("2d");

	function scan() {
		if (video.paused || video.ended) return;
		canvas.width = video.videoWidth;
		canvas.height = video.videoHeight;
		context.drawImage(video, 0, 0, canvas.width, canvas.height);
		const code = jsQR(
			context.getImageData(0, 0, canvas.width, canvas.height).data,
			canvas.width,
			canvas.height
		);

		if (code && !scanCooldown && code.data !== lastScannedCode) {
			lastScannedCode = code.data;
			scanCooldown = true;

			const qrInput = document.getElementById("qr_code");
			qrInput.value = code.data;

			const form = qrInput.closest("form");
			form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));

			setTimeout(() => {
				scanCooldown = false;
			}, 1500);
		}

		requestAnimationFrame(scan);
	}

	scan();
}