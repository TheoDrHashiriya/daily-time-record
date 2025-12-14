<div class="modal-container" id="department-create">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Create New Department</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form method="post" action="create-department">

			<div class="row">
				<div class="column">
					<label for="department_name">Department Name</label>
					<input type="text" name="department_name" id="department_name">
					<p class="error"><?= $errors["department_name"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="department_name">Abbreviation</label>
					<input type="text" name="abbreviation" id="abbreviation">
					<p class="error"><?= $errors["abbreviation"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="standard_am_time_in">Standard AM In</label>
					<input id="standard_am_time_in" type="time" name="standard_am_time_in" min="00:00" max="11:59">
					<p class="error"><?= $errors["standard_am_time_in"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="standard_am_time_out">Standard AM Out</label>
					<input id="standard_am_time_out" type="time" name="standard_am_time_out" min="00:00" max="11:59">
					<p class="error"><?= $errors["standard_am_time_out"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="standard_pm_time_in">Standard PM In</label>
					<input id="standard_pm_time_in" type="time" name="standard_pm_time_in" min="12:00" max="23:59">
					<p class="error"><?= $errors["standard_pm_time_in"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="standard_pm_time_out">Standard PM Out</label>
					<input id="standard_pm_time_out" type="time" name="standard_pm_time_out" min="12:00" max="23:59">
					<p class="error"><?= $errors["standard_pm_time_out"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>