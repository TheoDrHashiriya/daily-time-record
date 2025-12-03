<div class="card">
	<h2 class="header">Newest Records</h2>
	<?php if (!empty($records)): ?>
		<div class="record-table">
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>User</th>
						<th>Time In</th>
						<th>Time Out</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$limit = 5;
					$count = 0;
					foreach ($records as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["record_date"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td>
								<?= $row["time_in"] ? htmlspecialchars(
									date("h:i A", strtotime($row["time_in"]))
								) : "<em>No time in record.</em>" ?>
							</td>
							<td>
								<?= $row["time_out"] ? htmlspecialchars(
									date("h:i A", strtotime($row["time_out"]))
								) : "<em>No time out record.</em>" ?>
							</td>
						</tr>
						<?php
						$count++;
						if ($count === $limit)
							break;
						?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No records found.</p>
	<?php endif; ?>
</div>