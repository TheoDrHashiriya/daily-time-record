const qrButtons = document.querySelectorAll(".open-button.user-qr");

qrButtons.forEach(btn => {
	btn.addEventListener("click", async () => {
		const qrModal = document.getElementById("user-qr");
		const qrImage = qrModal.querySelector(".entity-image");

		const entityData = JSON.parse(btn.dataset.entityData);
		const userId = btn.dataset.entityId;
		const baseURL = entityData.base_url;
		const publicURL = entityData.public_url;

		qrImage.src = publicURL + "/assets/spinner.gif";
		const response = await fetch(baseURL + "/user-qr?id=" + userId);
		const base64 = await response.text();
		qrImage.src = base64;

	});
});