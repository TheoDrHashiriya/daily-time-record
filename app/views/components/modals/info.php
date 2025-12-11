<div class="modal-container" id="info">
	<div class="modal">
		<div class="row header">
			<h3 class="header"><i class="symbol fa-solid fa-info"></i><?= $_SESSION["message"]["info-title"] ?></h3>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>
		<p class="success"><?= $_SESSION["message"]["info"] ?></p>
	</div>
</div>