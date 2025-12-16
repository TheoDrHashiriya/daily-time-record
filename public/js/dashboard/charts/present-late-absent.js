new Chart(document.getElementById("present-late-absent"), {
	type: "pie",
	data: {
		labels: ["Present", "Late", "Absent"],
		datasets: [{
			data: [totals.present, totals.late, totals.absent],
			backgroundColor: ["blue", "orange", "red"],
		}]
	},
	options: {
		responsive: true,
		plugins: {
			tooltip: { enabled: true },
		}
	}
});