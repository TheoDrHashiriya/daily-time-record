<div class="modal-container" id="notification-create">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-pen-to-square"></i>Create New Notification</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form class="create-form" method="post" action="create-notification">
			<input type="hidden" name="entity_id" value="">

			<div class="row">
				<div class="column">
					<label for="created_by">Created By</label>
					<select id="created_by" name="created_by" required>
						<?php foreach ($users as $user)
							echo "<option value=\"{$user["id"]}\">{$user["full_name_formatted"]}</option>";
						?>"
					</select>
					<p class="error"><?= $errors["created_by"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="title">Title</label>
					<textarea id="title" name="title" row="1" maxlength="1000" placeholder="Please enter title."></textarea>
					<p class="error"><?= $errors["title"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="content">Content</label>
					<textarea id="content" name="content" row="1" maxlength="1000"
						placeholder="Please enter content."></textarea>
					<p class="error"><?= $errors["content"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>