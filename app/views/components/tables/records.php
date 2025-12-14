<div class="card">
	<div class="top">
		<h2 class="header">Daily Time Records</h2>
		<?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="right actions">
				<div class="row">
					<div class="search-container">
						<form action="" method="GET">
							<input type="search" name="records" id="records" placeholder="Search"
								value="<?= isset($records["search"]) ? htmlspecialchars($records["search"]) : "" ?>">
							<button type="submit"><i class="fa-solid fa-search"></i></button>
						</form>
					</div>

					<div class="search-container">
						<input id="event_date" type="date" name="event_date"
							value="<?= isset($records["event_date"]) ? htmlspecialchars($records["event_date"]) : "" ?>">
					</div>
				</div>

				<div class="row">
					<button type="button" class="open-button" data-target="#record-create" data-modal-type="record-create">
						<i class="fa-solid fa-user-plus"></i>Add Record
					</button>

					<a href="all-events" target="_blank" class="action">
						<i class="fa-solid fa-print"></i>Print to PDF
					</a>
				</div>
			</div>
		<?php endif ?>
	</div>

	<?php if (empty($records["data"])): ?>
		<p>No records found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Actions</th>
							<th>Event ID</th>
						<?php endif ?>
						<th>User No.</th>
						<th>User</th>
						<th>Type</th>
						<th>Date</th>
						<th>Time</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($records["data"] as $row): ?>
						<tr>
							<?php if (isset($isAdmin) && $isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#record-edit" data-modal-type="record-edit"
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

									<button type="button" class="open-button danger" data-target="#record-delete"
										data-modal-type="record-delete" data-entity-id="<?= $row["id"] ?>">
										<i class="fa-regular fa-trash-can"></i>Delete
									</button>
								</td>
								<td><?= htmlspecialchars($row["id"]) ?></td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["user_number"]) ?></td>
							<td><?= htmlspecialchars($row["full_name_formatted"] ?? "") ?></td>
							<td class="type"><?= htmlspecialchars($row["type_name_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["event_date_formatted"]) ?></td>
							<td>
								<?= $row["event_time"] ? htmlspecialchars($row["event_time_formatted"]) : "<em>No record.</em>" ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>