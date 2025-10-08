document.addEventListener("DOMContentLoaded", () => {
	const savedTheme = localStorage.getItem("theme") || "light";
	document.documentElement.setAttribute("data-theme", savedTheme);

	const themeToggle = document.getElementById("theme-toggle");

	themeToggle.innerHTML = savedTheme === "dark" ? "🌞" : "🌛";

	themeToggle.addEventListener("click", () => {
		const currentTheme = document.documentElement.getAttribute("data-theme");
		const newTheme = currentTheme === "dark" ? "light" : "dark";
		document.documentElement.setAttribute("data-theme", newTheme);
		localStorage.setItem("theme", newTheme);
		themeToggle.innerHTML = newTheme === "dark" ? "🌞" : "🌛";
	});
});
