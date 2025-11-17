window.addEventListener("DOMContentLoaded", () => {
	const infoPane = document.getElementById("info-pane");
	const authPane = document.getElementById("authentication-pane");
	if (infoPane && infoPane.textContent.trim() !== '') {
		infoPane.style.display = "block";
		authPane.style.display = "none";
		setTimeout(() => {
			infoPane.style.display = "none";
			window.location.href = "logout";
		}, 5000);
	}
});