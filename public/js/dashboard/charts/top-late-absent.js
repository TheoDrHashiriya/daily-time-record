new Chart(document.getElementById("top-late-absent"), {
	type: "bar",
	data: {
		labels: topLateAbsent.labels,
		datasets: [
			{
				label: "Late Count",
				data: topLateAbsent.late,
				backgroundColor: "orange",
			},
			{
				label: "Absent Count",
				data: topLateAbsent.absent,
				backgroundColor: "red",
			},
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