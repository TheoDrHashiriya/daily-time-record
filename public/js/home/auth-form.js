const authPane = document.querySelector(".authentication-pane");
const authButton = document.getElementById("auth-button");
const backButton = document.getElementById("back-button");

authButton.addEventListener("click", () => {
	fetch("ajax/auth-form")
		.then(res => res.text())
		.then(html => authPane.innerHTML = html);
});

backButton.addEventListener("click", () => {
	fetch("ajax/auth-button")
		.then(res => res.text())
		.then(html => authPane.innerHTML = html);
});
