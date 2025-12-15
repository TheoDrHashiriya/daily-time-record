<div class="card">
	<div class="top">
		<h2 class="header">System Log Types</h2>
	</div>

	<?php if (empty($system_log_types["data"])): ?>
		<p>No system log types found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<!-- <?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Actions</th>
						<?php endif ?> -->
						<th>ID</th>
						<th>Type Name</th>
						<th>Is Notification</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($system_log_types["data"] as $row): ?>
						<tr>
							<!-- <?php if (isset($isAdmin) && $isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#system-log-type-edit"
										data-modal-type="system-log-type-edit" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
										  	htmlspecialchars(
										  		json_encode([
										  			"type_name" => $row["type_name"],
										  			"is_notification" => $row["is_notification"],
										  		])
										  	) ?>">
										<i class="fa-regular fa-pen-to-square"></i>Edit
									</button>

									<button type="button" class="open-button danger" data-target="#system-log-type-delete"
										data-modal-type="system-log-type-delete" data-entity-id="<?= $row["id"] ?>">
										<i class="fa-regular fa-trash-can"></i>Delete
									</button>
								</td>
							<?php endif ?> -->
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["type_name_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["is_notification_formatted"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>