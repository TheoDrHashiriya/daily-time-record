const openFormButtons = document.querySelectorAll(".open-button[data-entity-data]");


openFormButtons.forEach(button => {
	button.addEventListener("click", () => {
		if (!button) return;

		const modalId = button.dataset.target;
		if (!modalId) return;

		const modalToShow = document.querySelector(modalId);
		if (!modalToShow) return;

		const form = modalToShow.querySelector("form");
		const entityId = button.dataset.entityId;
		let entityData = {};

		try {
			entityData = JSON.parse(button.dataset.entityData);
		} catch (e) {
			console.error("Failed to parse entity data:", e);
			return;
		}

		// PREFILLS

		const entityIdInput = form.querySelector('[name="entity_id"]');
		if (entityIdInput) entityIdInput.value = entityId;

		for (const key in entityData) {
			const input = form.querySelector(`#${key}`);
			if (!input) continue;

			if (input.type === "datetime-local" && entityData[key]) {
				const date = new Date(entityData[key]);
				const yyyy = date.getFullYear();
				const mm = String(date.getMonth() + 1).padStart(2, '0');
				const dd = String(date.getDate()).padStart(2, '0');
				const hh = String(date.getHours()).padStart(2, '0');
				const min = String(date.getMinutes()).padStart(2, '0');
				const sec = String(date.getSeconds()).padStart(2, '0');
				input.value = `${yyyy}-${mm}-${dd}T${hh}:${min}:${sec}`;
			} else {
				input.value = entityData[key];
			}
		}
	});
});