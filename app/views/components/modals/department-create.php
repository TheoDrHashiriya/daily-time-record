<div class="modal-container" id="department-create">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Create New Department</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form class="create-form" method="post" action="create-department">
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

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>