<div class="modal-container" id="user-qr">
	<div class="modal">
		<?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="row header">
				<h2><i class="symbol fa-solid fa-qrcode"></i>QR Code</h2>
				<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
			</div>
		<?php endif ?>

		<div class="row">
			<div class="column user-info">
				<p class="entity-data" name="full_name_formatted" id="full_name_formatted">
					<?= $user["full_name_formatted"] ?? "Lol" ?>
				</p>
				<p class="entity-data" name="user_number" id="user_number"><?= $user["user_number"] ?? "Lol" ?></p>
			</div>

			<div class="column">
				<img class="entity-image" data-key="qr_code_base64" alt="QR Code"
					src="<?= $user["qr_code_base64"] ?? "" ?>">
			</div>
		</div>

		<?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>

				<form action="qr-code" method="post" target="_blank" data-normal="1">
					<input type="hidden" name="entity_id" value="">
					<button type="submit" class="action">
						<i class="fa-solid fa-print"></i>Print to PDF
					</button>
				</form>

				<button class="open-button danger" type="button" data-target="#regenerate-qr" data-entity-id="">Regenerate QR
				</button>
			</div>
		<?php endif ?>
	</div>
</div>