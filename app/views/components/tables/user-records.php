<div class="card monthly-records">
	<div class="top">
		<h2 class="header"></i>DAILY TIME RECORD</h2>
	</div>

	<?php if (empty($records)): ?>
		<p>No records found.</p>
	<?php else: ?>

		<p>NAME: <b><?= $user["full_name_formatted"] ?? "" ?></b></p>
		<p>For the Month of: <b><?= $month ?? "" ?>, <?= $year ?? "" ?></b></p>

		<div class="table-container monthly-records">
			<table>
				<thead>
					<tr>
						<th rowspan="2">DAY</th>
						<th class="meridian" colspan="2">A.M.</th>
						<th class="meridian" colspan="2">P.M.</th>
						<th colspan="2" rowspan="2">UNDERTIME</th>
					</tr>

					<tr>
						<th>ARRIVAL</th>
						<th>DEPARTURE</th>
						<th>ARRIVAL</th>
						<th>DEPARTURE</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$days = 31;
					foreach ($records as $day => $row): ?>
						<tr>
							<td><?= (int) date("d", strtotime($day)) ?></td>
							<td><?= htmlspecialchars($row["am_in"] ?? "-") ?></td>
							<td><?= htmlspecialchars($row["am_out"] ?? "-") ?></td>
							<td><?= htmlspecialchars($row["pm_in"] ?? "-") ?></td>
							<td><?= htmlspecialchars($row["pm_out"] ?? "-") ?></td>
							<td><?= htmlspecialchars($row["undertime"] ?? "-") ?></td>
							<td><?= htmlspecialchars($row["status"] ?? "-") ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<p>I CERTIFY on my honor that the above is a true and correct report of the hours of work performed, record of
				which was made DAILY at the time of arrival and at the time of departure from office.</p>
		</div>
	<?php endif; ?>
</div>