new Chart(document.getElementById("attendance-this-week"), {
	type: "bar",
	data: {
		labels: attendanceThisWeek.labels,
		datasets: [
			{ label: "Present", data: attendanceThisWeek.present, backgroundColor: "blue" },
			{ label: "Late", data: attendanceThisWeek.late, backgroundColor: "orange" },
			{ label: "Absent", data: attendanceThisWeek.absent, backgroundColor: "red" },
		]
	},
	options: {
		responsive: true,
		scales: {
			x: { stacked: true },
			y: { stacked: true, beginAtZero: true }
		}
	}
});