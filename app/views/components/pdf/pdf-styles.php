<style>
	@font-face {
		font-family: "Inter";
		src: url("<?= realpath(FONTS_PATH . '/Inter/static/Inter_18pt-Regular.ttf') ?>") format("truetype");
		font-weight: normal;
		font-style: normal;
	}

	@font-face {
		font-family: "Inter";
		src: url("<?= realpath(FONTS_PATH . '/Inter/static/Inter_18pt-Bold.ttf') ?>");
		font-weight: bold;
		font-style: normal;
	}

	* {
		box-sizing: border-box;
		font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
	}

	/* RECORD TABLE */

	.table-container .type {
		font-weight: bold;
	}

	/* MONTHLY RECORDS */

	.card.monthly-records .top {
		justify-content: center;
	}

	.card.monthly-records p {
		text-align: left;
		font-size: 16px;
	}

	.card.monthly-records h2 {
		text-align: center;
		padding: 0.5rem;
		border: 1px solid black;
		font-weight: bolder;
	}

	.monthly-records table {
		border-radius: unset;
	}

	.monthly-records thead tr th {
		text-align: center;
	}

	.monthly-records th,
	.monthly-records td {
		border: 0.1px solid black;
		font-weight: 700;
		text-align: center;
		padding: 0.1rem 0;
	}

	.monthly-records .meridian {
		font-size: x-large;
		font-weight: 800;
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
		font-weight: bold;
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
		font-weight: bold;
	}
</style>