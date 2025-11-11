<style>
	@import url("https://fonts.googleapis.com/css?family=Inter");

	* {
		box-sizing: border-box;
		font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
	}

	/* RECORD TABLE */

	.table-container .type {
		font-weight: bold;
	}

	:root {
		/* INDEX PAGE */
		--header: ;
		--sidebar: #1c1f23;
		--sidebar-link: #35bcfffd;
		--main: ;
		--card: #fafafa;

		/* OTHERS */
		--bg: #fafafa;
		--bg-gradient: linear-gradient(to right, #c0c0c0, #eeeeee);
		--border: #c0c0c0;
		--text: #202020;
		--header-text: #000000;
		--secondary: #0000aa;
		--button: #0000aa;
		--tertiary: #00aa00;
		--highlight: rgba(255, 255, 0, 0.25);
		--error: red;
		--table-header: #ece5e5;
		--table-border: #d1c7c7;
		--table-even: #e9e0e0;
	}

	/* TABLES */

	table {
		border: 1px solid #d1c7c7;
		border-spacing: 0;
		border-radius: 10px;
		border-collapse: separate;
		text-align: center;
		font-size: 0.9rem;
		width: 100%;
		margin-bottom: 20px;
	}

	tr:nth-child(even) {
		background: #e9e0e0;
	}

	th,
	td {
		text-align: left;
		padding: 10px;
	}

	th {
		font-weight: 600;
		background-color: var(--table-header);
		color: var(--header-text);
	}

	td {
		color: var(--text);
	}

	h1,
	h2,
	h3,
	h4 {
		font-weight: 600;
	}
</style>