const openFormButtons = document.querySelectorAll(".open-button");

openFormButtons.forEach(button => {
	button.addEventListener("click", () => {
		const modalId = button.dataset.target;
		if (!modalId) return;

		const modal = document.querySelector(modalId);
		if (!modal) return;

		const form = modal.querySelector("form");
		if (!form) return;

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

		// Prefill entity ids in edit modals
		const entityIdInput = form.querySelector('[name="entity_id"]');
		if (entityIdInput) entityIdInput.value = entityId || "";

		form.querySelectorAll("input, textarea, select").forEach(input => {
			const key = input.id || input.name;

			let value = entityData[key];

			if (input.type === "datetime-local" && value === undefined) {
				const now = new Date();
				const yyyy = now.getFullYear();
				const mm = String(now.getMonth() + 1).padStart(2, '0');
				const dd = String(now.getDate()).padStart(2, '0');
				// hardcoded plus 8, eww
				const hh = String(now.getHours() + 8).padStart(2, '0');
				const min = String(now.getMinutes()).padStart(2, '0');
				const sec = String(now.getSeconds()).padStart(2, '0');
				input.value = `${yyyy}-${mm}-${dd}T${hh}:${min}:${sec}`;
			} else if (value === undefined)
				value = "";
			else
				input.value = value;
		});

		// for (const key in entityData) {
		// 	const input = form.querySelector(`#${key}`);
		// 	if (!input) continue;

		// 	if (input.type === "datetime-local") {
		// 		const rawValue = entityData[key] || new Date();
		// 		const date = new Date(rawValue);
		// 		const yyyy = date.getFullYear();
		// 		const mm = String(date.getMonth() + 1).padStart(2, '0');
		// 		const dd = String(date.getDate()).padStart(2, '0');
		// 		const hh = String(date.getHours()).padStart(2, '0');
		// 		const min = String(date.getMinutes()).padStart(2, '0');
		// 		const sec = String(date.getSeconds()).padStart(2, '0');
		// 		input.value = `${yyyy}-${mm}-${dd}T${hh}:${min}:${sec}`;
		// 	} else {
		// 		input.value = entityData[key];
		// 	}
		// }
	});
});