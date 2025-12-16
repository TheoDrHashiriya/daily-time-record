new Chart(document.getElementById("top-absent"), {
	type: "bar",
	data: {
		labels: topAbsent.labels,
		datasets: [
			{
				label: "Absent Count",
				data: topAbsent.absent,
				backgroundColor: "red",
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