const form = document.getElementById("authenticate");
const input = form.querySelector("#user_number");

// Remove input autofocus for mobile devices
if (/Mobi|Android/i.test(navigator.userAgent))
	input.removeAttribute("autofocus");

input.addEventListener("input", () => {
	input.value = input.value.replace(/\D/g, '');

	if (input.value.length === 4) {
		// form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
		// form.submit();
		submitUserNumber();
	}
});

function submitUserNumber() {
	const data = new FormData(form);

	fetch(form.action, {
		method: "POST",
		body: data
	})
		.then(res => res.json())
		.then(response => {
			if (!response.success) {
				if (response.errors) {
					const errorEl = form.querySelector(".error");
					errorEl.textContent = response.errors.user_number || "Invalid input uwu";
				}
				return;
			}

			if (response.user_number_is_admin === true)
				showLoginUsernamePasswordModal();
			else {
				import(window.qrLoginConfig.jsQR)
					.then(() => showLoginQrCodeModal())
					.catch(err => {
						console.error("Failed to load QR scripts:", err);
						showErrorModal();
					});
			}
		});
}
