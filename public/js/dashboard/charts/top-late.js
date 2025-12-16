new Chart(document.getElementById("top-late"), {
	type: "bar",
	data: {
		labels: topLate.labels,
		datasets: [
			{
				label: "Late Count",
				data: topLate.late,
				backgroundColor: "orange",
			}
		]
	},
	options: {
		responsive: true,
		indexAxis: "y",
		plugins: {
			legend: { position: "top" },
			tooltip: { enabled: true },
		}
	}
});