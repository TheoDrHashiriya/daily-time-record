const badge = document.querySelector(".notification-badge");
const bell = document.querySelector(".notification-bell");
const dropdown = document.querySelector(".notification-dropdown");

document.addEventListener("DOMContentLoaded", (e) => {
	badgeContent = parseInt(badge.textContent);
	if (badgeContent > 0)
		badge.classList.add("show");
	else
		badge.classList.remove("show");
});

bell.addEventListener("click", (e) => {
	dropdown.classList.toggle("show");

	const dropdownIsVisible = dropdown.classList.contains("show");
	const badgeIsVisible = badge.classList.contains("show");
	if (dropdownIsVisible && badgeIsVisible)
		setTimeout(markAsRead(), 3000);
});

document.addEventListener("click", (e) => {
	if (!dropdown.contains(e.target) && !bell.contains(e.target))
		dropdown.classList.remove("show");
});

async function markAsRead() {
	try {
		await fetch("mark-read", { method: "POST" });
		badge.classList.remove("show");
	} catch (e) {
		console.error("Failed to mark notifications as read:", e);
	}
};