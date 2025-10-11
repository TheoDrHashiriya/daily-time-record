document.addEventListener("DOMContentLoaded", () => {
	const lastSection = localStorage.getItem("lastSection") || "home-section";
	document.getElementById(lastSection).style.display = "grid";

	document.querySelectorAll(".sidebar a").forEach((link) => {
		link.addEventListener("click", function (e) {
			e.preventDefault();

			document
				.querySelectorAll(".section")
				.forEach((section) => (section.style.display = "none"));

			const targetId = link.dataset.target;
			document.getElementById(targetId).style.display = "grid";
			localStorage.setItem("lastSection", targetId);
		});
	});
});
