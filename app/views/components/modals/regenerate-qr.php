<div class="modal-container" id="regenerate-qr">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-exclamation"></i>Regenerate QR Code</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form method="post" action="regenerate-qr">
			<input type="hidden" name="entity_id" value="">

			<p>Are you sure you want to generate a new QR code for the current user? Current QR code will be expired.</p>
			<p class="error-general"><?= $errors["general"] ?? "" ?></p>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button class="danger" type="submit">Go</button>
			</div>
		</form>
	</div>
</div>