<div class="modal-container" id="error">
	<div class="modal">
		<div class="row header">
			<h3 class="header"><i
					class="symbol fa-regular fa-face-frown"></i><?= $_SESSION["message"]["error-title"] ?? "Error!" ?>
			</h3>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>
		<p class="error-general"><?= $_SESSION["message"]["error"] ?></p>
	</div>
</div>