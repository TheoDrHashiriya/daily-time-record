<div class="modal-container" id="notification-delete">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-exclamation"></i>Delete Notification</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form class="delete-form" method="post" action="delete-notification">
			<input type="hidden" name="entity_id" value="">

			<p>Are you sure you want to delete the current notification?</p>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button class="danger" type="submit">Go</button>
			</div>
		</form>
	</div>
</div>