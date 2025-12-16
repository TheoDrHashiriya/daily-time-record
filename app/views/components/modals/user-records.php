<div class="modal-container" id="user-records">
	<div class="modal">
		<div class="row header">
			<h2 class="header"><i class="symbol fa-solid fa-calendar"></i>User Records</h2>
			<button type="button" class="close-button icon"><i class="fa-solid fa-x"></i></button>
		</div>

		<form method="GET" action="user-records" target="_blank">

			<div class="row">
				<div class="column">
					<label for="user_id">User</label>
					<select id="user_id" name="user_id">
						<?php foreach ($users["data"] as $index => $user): ?>
							<option value="<?= $user["id"] ?>" <?= $index === 0 ? "selected" : "" ?>>
								<?= $user["full_name_formatted"] ?>
							</option>
						<?php endforeach ?>
					</select>
					<p class="error"><?= $errors["user_id"] ?? "" ?></p>
				</div>
			</div>

			<div class="row">
				<div class="column">
					<label for="year">Year</label>
					<select name="year" id="year">
						<?php for ($y = date("Y"); $y >= 2000; $y--): ?>
							<option value="<?= $y ?>" <?php if ($y === date("Y"))
								  echo "selected"; ?>>
								<?= $y ?>
							</option>
						<?php endfor ?>
					</select>
					<p class="error"><?= $errors["year"] ?? "" ?></p>
				</div>

				<div class="column">
					<label for="month">Month</label>
					<select name="month" id="month">
						<option value="01" selected>January</option>
						<option value="02">February</option>
						<option value="03">March</option>
						<option value="04">April</option>
						<option value="05">May</option>
						<option value="06">June</option>
						<option value="07">July</option>
						<option value="08">August</option>
						<option value="09">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
					<p class="error"><?= $errors["month"] ?? "" ?></p>
				</div>
			</div>

			<div class="modal-actions">
				<button class="close-button" type="button" id="cancel">Cancel</button>
				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>