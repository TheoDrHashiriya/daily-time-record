const qrCodeModal = document.getElementById("login-qr-code");

const qrCodeCloseButtons = qrCodeModal.querySelectorAll(".close-button");
qrCodeCloseButtons.forEach(button => {
	button.addEventListener("click", closeModal);
});

let stream;
let video = qrCodeModal.querySelector("video");

async function initCamera() {
	if (stream) return;

	try {
		stream = await navigator.mediaDevices.getUserMedia({
			video: {
				facingMode: "environment",
				width: 640,
				height: 640,
			}
		});
		video.srcObject = stream;
		await video.play();
		startScanning(video);
	} catch (err) {
		alert("Camera init failed:", err);
	}
}

function showLoginQrCodeModal() {
	initCamera();
	qrCodeModal.classList.add("show");
}

function closeModal() {
	try {
		if (stream) {
			stream.getTracks().forEach(track => track.stop());
			stream = null;
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