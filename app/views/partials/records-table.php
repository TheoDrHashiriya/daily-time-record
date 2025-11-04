<div class="card">
	<h2 class="header">Daily Time Records</h2>
	<?php if (!empty($records)): ?>
		<div class="record-table">
			<table>
				<thead>
					<tr>
						<th>User ID</th>
						<th>User</th>
						<th>Type</th>
						<th>Date</th>
						<th>Time</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($records as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["user_id"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td><?= htmlspecialchars($row["event_type"]) ?></td>
							<td><?= htmlspecialchars(date("l, M. j, Y", strtotime($row["event_date"]))) ?></td>
							<td>
								<?= $row["event_time"] ? htmlspecialchars(
									date("h:i A", strtotime($row["event_time"]))
								) : "<em>No record.</em>" ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No records found.</p>
	<?php endif; ?>
</div>