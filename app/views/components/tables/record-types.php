<div class="card">
	<div class="top">
		<h2 class="header">Record Types</h2>
		<?php if ($isAdmin): ?>
			<div class="right actions">
				<button type="button" class="open-button" data-target="#record-type-create" data-modal-type="record-type-create">
					<i class="fa-solid fa-user-plus"></i>Add Record Type
				</button>

				<a href="all-event-types" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
			</div>
		<?php endif ?>
	</div>

	<?php if (empty($records)): ?>
		<p>No record types found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if ($isAdmin): ?>
							<th>Actions</th>
						<?php endif ?>
						<th>ID</th>
						<th>Type Name</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($record_types as $row): ?>
						<tr>
							<?php if ($isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#record-type-edit"
										data-modal-type="record-type-edit" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
										  	htmlspecialchars(json_encode(["type_name" => $row["type_name"]])) ?>"><i
											class="fa-regular fa-pen-to-square"></i>Edit</button>

									<form action="delete-record-type" method="post" class="action">
										<input type="hidden" name="row-id" value="<?= $row["id"] ?>">
										<button class="danger" type="submit"
											onclick="return confirm('Are you sure you want to delete the current record type <?= $row['type_name_formatted'] ?>?')"><i
												class="fa-regular fa-trash-can"></i>Delete</button>
									</form>
								</td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td class="type"><?= htmlspecialchars($row["type_name_formatted"]) ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
</div>