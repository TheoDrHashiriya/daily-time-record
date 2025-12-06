const form = document.getElementById("auth-form-code");
const input = document.getElementById("code");

input.addEventListener("input", () => {
	input.value = input.value.replace(/\D/g, '');

	if (input.value.length === 4)
		form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
});