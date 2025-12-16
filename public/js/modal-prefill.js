const openFormButtons = document.querySelectorAll(".open-button");

openFormButtons.forEach(button => {
	button.addEventListener("click", () => {
		const modalId = button.dataset.target;
		if (!modalId) return;

		const modal = document.querySelector(modalId);
		if (!modal) return;

		if (modal.id === "user-records") {
			// modal.classList.add("show");
			return;
		}

		const entityId = button.dataset.entityId;
		let entityData = {};
		if (button.dataset.entityData) {
			try {
				entityData = JSON.parse(button.dataset.entityData);
			} catch (e) {
				console.error("Invalid entity data JSON:", e);
				entityData = {};
			}
		}

		// Prefill generally
		modal.querySelectorAll(".entity-data").forEach(text => {
			const key = text.id || text.name;
			let value = entityData[key];
			if (value === undefined)
				text.textContent = "No one";
			else
				text.textContent = value;
		});

		// Prefill img elements for QR codes
		modal.querySelectorAll("img.entity-image").forEach(img => {
			const key = img.dataset.key;
			if (!key) return;

			const base64 = entityData[key];
			if (base64)
				img.src = base64;
			else
				img.src = ""; // Can be a placeholder image
		});

		const form = modal.querySelector("form");
		if (!form) return;

		// Prefill entity ids in edit modals
		const entityIdInput = form.querySelector('[name="entity_id"]');
		if (entityIdInput) entityIdInput.value = entityId || "";

		form.querySelectorAll("input, textarea, select").forEach(input => {
			const key = input.id || input.name;
			let value = entityData[key];

			if (input.type !== "hidden" && (value === undefined || value === null)) value = "";

			if (input.type === "datetime-local" && value === "") {
				const now = new Date();
				const yyyy = now.getFullYear();
				const mm = String(now.getMonth() + 1).padStart(2, '0');
				const dd = String(now.getDate()).padStart(2, '0');
				const hh = String(now.getHours()).padStart(2, '0');
				const min = String(now.getMinutes()).padStart(2, '0');
				const sec = String(now.getSeconds()).padStart(2, '0');
				input.value = `${yyyy}-${mm}-${dd}T${hh}:${min}:${sec}`;
			} else if (value === undefined)
				value = "";
			else
				input.value = value;
		});
	});
});