<div class="card">
	<h1 class="header">Daily Time Records</h1>
	<?php if (!empty($records)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>IN/OUT</th>
						<th>Date</th>
						<th>Time</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($records as $row): ?>
						<tr>
							<td><?= htmlspecialchars($row["user_id"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td class="type"><?= htmlspecialchars(GlobalHelper::formatEventType($row["type_name"])) ?></td>
							<td><?= htmlspecialchars(GlobalHelper::formatDate($row["event_time"])) ?></td>
							<td>
								<?= $row["event_time"] ? htmlspecialchars(GlobalHelper::formatTime($row["event_time"])) : "<em>No record.</em>" ?>
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