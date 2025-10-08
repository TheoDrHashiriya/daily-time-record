function updateClock() {
	const now = new Date();
	const timeString = now.toLocaleTimeString("en-US", {
		weekday: "long",
		month: "long",
		day: "numeric",
		year: "numeric",
		hour: "numeric",
		minute: "numeric",
		second: "numeric",
		hour12: true,
	});
	document.getElementById("clock").textContent = timeString;
}

updateClock();

setInterval(updateClock, 1000);
