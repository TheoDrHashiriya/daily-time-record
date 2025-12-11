<div class="modal-container" id="department-edit">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Edit Department</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form class="edit-form" method="post" action="edit-department">
			<input type="hidden" name="entity_id" value="">

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
					<label for="standard_time_in">Standard Time In</label>
					<input id="standard_time_in" type="time" name="standard_time_in">
					<p class="error"><?= $errors["standard_time_in"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="standard_time_out">Standard Time Out</label>
					<input id="standard_time_out" type="time" name="standard_time_out">
					<p class="error"><?= $errors["standard_time_out"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>