<div class="card">
	<div class="top">
		<h2 class="header">Daily Time Records</h2>
		<a href="all-events" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
	</div>
	<?php if (!empty($records)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>Actions</th>
						<th>Event ID</th>
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
							<td class="actions">
								<button type="button" class="open-button" data-target="#edit-record" data-modal-type="edit-record"
									data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
									  	htmlspecialchars(
									  		json_encode([
									  			"user_id" => $row["user_id"],
									  			"event_type" => $row["event_type"],
									  			"event_time" => $row["event_time"]
									  		])
									  	) ?>">
									<i class="fa-regular fa-pen-to-square"></i>Edit
								</button>

								<button type="button" class="open-button danger" data-target="#delete-record"
									data-modal-type="delete-record" data-entity-id="<?= $row["id"] ?>">
									<i class="fa-regular fa-trash-can"></i>Delete
								</button>
							</td>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["user_id"]) ?></td>
							<td><?= htmlspecialchars($row["user"]) ?></td>
							<td class="type"><?= htmlspecialchars($row["type_name_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["event_date_formatted"]) ?></td>
							<td>
								<?= $row["event_time"] ? htmlspecialchars($row["event_time_formatted"]) : "<em>No record.</em>" ?>
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