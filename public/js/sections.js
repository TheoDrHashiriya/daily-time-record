document.addEventListener("DOMContentLoaded", () => {
	const lastSection = localStorage.getItem("lastSection") || "home-section";

	const links = document.querySelectorAll("a.nav");
	const sections = document.querySelectorAll(".section");

	function showSection(targetId) {
		sections.forEach((section) => (section.style.display = "none"));

		const targetSection = document.getElementById(targetId);
		if (!targetSection) {
			console.warn(`No section found with id "${targetId}"`);
			return;
		}
		targetSection.style.display = "flex";

		links.forEach((link) =>
			link.classList.toggle("selected", link.dataset.target === targetId));

		localStorage.setItem("lastSection", targetId);
	}

	showSection(lastSection);

	links.forEach((link) => {
		link.addEventListener("click", (e) => {
			if (link.classList.contains("sidebar-link"))
				e.preventDefault();
			showSection(link.dataset.target);
		})
	});

	document.querySelector(".sidebar a#logout").addEventListener("click", () => {
		localStorage.setItem("lastSection", "home-section");
	});
});
