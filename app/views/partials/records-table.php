<div class="card">
	<h2 class="header">Daily Time Records</h2>
	<?php if (!empty($records)): ?>
		<div class="record-table">
			<table>
				<thead>
					<tr>
						<th>User ID</th>
						<th>User</th>
						<th>Date</th>
						<th>Time In</th>
						<th>Time Out</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($records as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["user_id"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td><?= htmlspecialchars(date("l, M. j, Y", strtotime($row["record_date"]))) ?></td>
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
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No records found.</p>
	<?php endif; ?>
</div>