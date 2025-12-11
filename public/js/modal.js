const openButtons = document.querySelectorAll(".open-button");
const closeButtons = document.querySelectorAll(".close-button");

const modalContainers = document.querySelectorAll(".modal-container");

function showLoginUsernamePasswordModal() {
	const modalUsernamePassword = document.getElementById("login-username-password");
	modalUsernamePassword.classList.add("show");
}

function showSuccessModal() {
	const successModal = document.getElementById("success");
	successModal.classList.add("show");
}

function showInfoModal() {
	const infoModal = document.getElementById("info");
	infoModal.classList.add("show");
}

function showErrorModal() {
	const errorModal = document.getElementById("error");
	errorModal.classList.add("show");
}

openButtons.forEach(button => {
	button.addEventListener("click", () => {
		const modalId = button.dataset.target;
		const modalToShow = document.querySelector(modalId);

		if (!modalToShow) return;

		// Clear previous errors
		modalToShow.querySelectorAll(".error").forEach(line => line.textContent = "");
		modalToShow.querySelectorAll(".error-general").forEach(line => line.textContent = "");
		modalToShow.classList.add("show");
	});
});

closeButtons.forEach(button => {
	button.addEventListener("click", () => {
		const modalToClose = button.closest(".modal-container");
		if (!modalToClose) return;
		modalToClose.classList.remove("show");
	})
})

modalContainers.forEach(modalContainer => {
	modalContainer.addEventListener("click", (e) => {
		if (e.target !== modalContainer) return;

		try {
			stream.getTracks().forEach(track => track.stop());
			stream = null;
		} catch (e) {
			console.log("No stream detected.");
		}

		modalContainer.classList.remove("show");
	});
});