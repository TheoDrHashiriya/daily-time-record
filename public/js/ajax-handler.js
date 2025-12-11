document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll("form").forEach(form => {
		if (form.dataset.normal === "1") return;

		form.addEventListener("submit", async (e) => {
			e.preventDefault();

			// Clear previous errors
			form.querySelectorAll(".error").forEach(line => line.textContent = "");
			form.querySelectorAll(".error-general").forEach(line => line.textContent = "");

			const formData = new FormData(form);
			const action = form.getAttribute("action");

			let url = action;
			let options = {
				method: form.method?.toUpperCase() || "POST",
				credentials: "include"
			};

			if (options.method === "GET") {
				const params = new URLSearchParams(new FormData(form));
				url += "?" + params.toString();
			} else
				options.body = new FormData(form);

			const response = await fetch(url, options);

			const contentType = response.headers.get("Content-Type");

			let result;

			if (!contentType.includes("application/json")) {
				const text = await response.text();
				console.log("Non-JSON response:", text);
				form.dataset.normal = "1";
				form.submit();
				return;
			}

			result = await response.json();
			console.log("Parsed JSON:", result);

			if (result.success) {
				form.reset();
				form.closest(".modal-container").classList.remove("show");

				if (result.logoutAfter)
					window.location.href = "logout";
				else
					window.location.href = "dashboard";
			} else if (result.errors) {
				for (const field in result.errors) {
					if (field === "general") {
						const errorLine = form.querySelector('.error-general');
						errorLine.textContent = result.errors[field];
					}

					const fieldLine = form.querySelector(`[name="${field}"]`);

					if (!fieldLine) continue;

					const errorLine = fieldLine.nextElementSibling;

					if (errorLine && errorLine.classList.contains("error") || errorLine.classList.contains("error-general"))
						errorLine.textContent = result.errors[field];
				}
			}
		});
	});
});