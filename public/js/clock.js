function updateDate() {
	const date = document.getElementById("date-text");
	const now = new Date();
	const dateString = now.toLocaleDateString("en-US", {
		dateStyle: "full",
	});
	date.textContent = dateString;
}

function updateTime() {
const time = document.getElementById("time-text");
	const now = new Date();
	const timeString = now.toLocaleTimeString("en-US", {
		hour: "numeric",
		minute: "numeric",
		second: "numeric",
		hour12: true,
	});
	time.textContent = timeString;

	if (timeString.startsWith("12:00 AM"))
		updateDate();
}

updateDate();
updateTime();
setInterval(updateTime, 1000);
