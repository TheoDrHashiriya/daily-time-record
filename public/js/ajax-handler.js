document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll("form").forEach(form => {
		if (form.dataset.normal === "1") return;

		let submitting = false;

		form.addEventListener("submit", async (e) => {
			e.preventDefault();
			e.stopImmediatePropagation();
			if (submitting) return;
			submitting = true;

			const submitBtn = form.querySelector('[type="submit"]');
			if (submitBtn) submitBtn.disabled = true;

			// Clear previous errors
			form.querySelectorAll(".error").forEach(line => line.textContent = "");
			form.querySelectorAll(".error-general").forEach(line => line.textContent = "");

			const action = form.getAttribute("action");

			let url = action;

			let method = (form.method || "GET").toUpperCase();
			let options = { method, credentials: "include" };

			if (method === "POST")
				options.body = new FormData(form);
			else {
				const params = new URLSearchParams(new FormData(form));
				url += "?" + params.toString();
			}

			try {
				const response = await fetch(url, options);
				const contentType = response.headers.get("Content-Type");

				if (!contentType.includes("application/json")) {
					const text = await response.text();
					console.log("Non-JSON response:", text);
					form.dataset.normal = "1";
					// form.submit();
					// form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
					// window.location.href = action;
					return;
				}

				const result = await response.json();
				console.log("Parsed JSON:", result);

				if (result.success) {
					form.reset();
					form.closest(".modal-container").classList.remove("show");

					if (result.redirect) window.location.href = result.redirect;
					else if (result.logoutAfter)
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
			} catch (err) {
				console.error("Form submit error:", err);
			} finally {
				submitting = false;
				if (submitBtn) submitBtn.disabled = false;
			}
		});
	});
});