<div class="modal-container" id="login-qr-code">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-qrcode"></i>Scan Your QR Code</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<!-- <div class="modal-overlay"><i class="fa-solid fa-hand-pointer"></i>Click anywhere to start scanning.</div> -->

		<video id="qr-video" width="300" height="250" disablePictureInPicture></video>

		<form class="login-form" method="post" action="login-qr">
			<input type="hidden" name="qr_code" id="qr_code">
			<p class="error"><?= $errors["qr_code"] ?? "" ?></p>
		</form>

		<p>Hover your printed QR code over the camera.</p>
	</div>
</div>