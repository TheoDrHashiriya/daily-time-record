<div class="modal-container" id="record-edit">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Edit Record</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form class="edit-form" method="post" action="edit-record">
			<input type="hidden" name="entity_id" value="">

			<label for="user_id">Created By</label>
			<select id="user_id" name="user_id" required>
				<?php foreach ($users as $user)
					echo "<option value=\"{$user["id"]}\">{$user["full_name_formatted"]}</option>";
				?>"
			</select>
			<p class="error"><?= $errors["user_id"] ?? "" ?></p>

			<div class="row">
				<div class="column">
					<label for="event_time">Date</label>
					<input id="event_time" type="datetime-local" step="1" name="event_time">
					<p class="error"><?= $errors["event_time"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="event_type">Record Type</label>
					<select id="event_type" name="event_type" required>
						<?php foreach ($record_types as $type)
							echo "<option value=\"{$type["id"]}\">{$type["type_name_formatted"]}</option>";
						?>
					</select>
					<p class="error"><?= $errors["event_type"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>