window.addEventListener("DOMContentLoaded", () => {
	const infoPane = document.getElementById("info-pane");
	if (infoPane && infoPane.textContent.trim() !== '') {
		infoPane.style.display = "block";
		setTimeout(() => {
			infoPane.style.display = "none";
			window.location.href = "logout";
		 }, 5000);
	}
});