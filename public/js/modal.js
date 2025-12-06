const openButtons = document.querySelectorAll(".open-button");
const closeButtons = document.querySelectorAll(".close-button");
const modalContainers = document.querySelectorAll(".modal-container");

function showSuccessModal() {
	const successModal = document.querySelector("#success");
	successModal.classList.add("show");
}

function showErrorModal() {
	const errorModal = document.querySelector("#error");
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
		modalContainer.classList.remove("show");
	});
});