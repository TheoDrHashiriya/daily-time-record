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

	.row {
		display: block;
		white-space: nowrap;
	}

	.column {
		display: inline-block;
		vertical-align: middle;
		width: 48%;
		box-sizing: border-box;
	}

	.column:last-child {
		margin-right: 0;
	}

	.entity-image {
		max-width: 100%;
		height: auto;
	}

	.user-info #full_name_formatted {
		font-size: 1.5rem;
		font-weight: 700;
		text-align: center;
	}

	.user-info #user_number {
		margin: 0;
		text-align: center;
		font-size: 4rem;
	}
</style>