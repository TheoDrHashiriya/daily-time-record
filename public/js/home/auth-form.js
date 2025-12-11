const form = document.getElementById("authenticate");
const input = document.getElementById("user_number");

input.addEventListener("input", () => {
	input.value = input.value.replace(/\D/g, '');

	if (input.value.length === 4)
		form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
});