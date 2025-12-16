new Chart(document.getElementById("attendance-past-week"), {
	type: "bar",
	data: {
		labels: attendancePastWeek.labels,
		datasets: [
			{ label: "Present", data: attendancePastWeek.present, backgroundColor: "blue" },
			{ label: "Late", data: attendancePastWeek.late, backgroundColor: "orange" },
			{ label: "Absent", data: attendancePastWeek.absent, backgroundColor: "red" },
		]
	},
	options: {
		responsive: true,
		scales: {
			x: { stacked: true },
			y: { stacked: true, beginAtZero: true }
		},
		plugins: {
			title: {
				display: false,
				text: "Attendance for the past week",
				tooltip: { enabled: true },
			}
		}
	}
});