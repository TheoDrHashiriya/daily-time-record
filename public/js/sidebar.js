document.addEventListener("DOMContentLoaded", () => {
	const hamburger = document.querySelector(".hamburger-lines");
	const container = document.querySelector(".dashboard-container");
	const sidebar = document.querySelector(".sidebar");

	hamburger.addEventListener("click", () => {
		if (window.innerWidth <= 800) {
			sidebar.classList.toggle("active");
		 } else {
			container.classList.toggle("sidebar-hidden");
			sidebar.classList.toggle("hidden");
		}
	});
	
	document.querySelectorAll("#sidebar a").forEach(link => {
		link.addEventListener("click", () => {
			sidebar.classList.remove("active");
		})
	});
});