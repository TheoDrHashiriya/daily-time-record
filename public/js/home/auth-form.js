const form = document.getElementById("authenticate");
const input = form.querySelector("#user_number");

// Remove input autofocus for mobile devices
if (/Mobi|Android/i.test(navigator.userAgent))
	input.removeAttribute("autofocus");

input.addEventListener("input", () => {
	input.value = input.value.replace(/\D/g, '');

	if (input.value.length === 4)
		form.dispatchEvent(new Event("submit", { bubbles: true, cancelable: true }));
});