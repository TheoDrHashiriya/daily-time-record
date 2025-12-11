const qrCodeModal = document.getElementById("login-qr-code");
let stream;

const qrCodeCloseButtons = qrCodeModal.querySelectorAll(".close-button");
qrCodeCloseButtons.forEach(button => {
	button.addEventListener("click", closeModal);
});

function showLoginQrCodeModal() {
	navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
		.then(s => {
			stream = s;
			const video = qrCodeModal.querySelector("video");
			video.srcObject = stream;
			video.play();

			video.addEventListener("loadedmetadata", () => setTimeout(() => startScanning(video), 500));
		})
		.catch(err => alert("Camera access denied: " + err));

	qrCodeModal.classList.add("show");
}

function closeModal() {
	try {
		stream.getTracks().forEach(track => track.stop());
		stream = null;
	} catch (e) {
		console.log("No stream detected.");
	}

	qrCodeModal.classList.remove("show");
}

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

		if (code) {
			// console.log("QR Code string:", code.data);
			const qrInput = document.getElementById("qr_code");
			qrInput.value = code.data;

			const form = qrInput.closest("form");
			form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
		}

		setTimeout(() => {
			requestAnimationFrame(scan);
		}, 500);
	}

	scan();
}