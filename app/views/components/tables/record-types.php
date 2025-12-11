<div class="card">
	<div class="top">
		<h2 class="header">Record Types</h2>
		<!-- <?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="right actions">
				<button type="button" class="open-button" data-target="#record-type-create"
					data-modal-type="record-type-create">
					<i class="fa-solid fa-user-plus"></i>Add Record Type
				</button>
			</div>
		<?php endif ?> -->
	</div>

	<?php if (empty($record_types["data"])): ?>
		<p>No record types found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Actions</th>
						<?php endif ?>
						<th>ID</th>
						<th>Type Name</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($record_types["data"] as $row): ?>
						<tr>
							<?php if ($isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#record-type-edit"
										data-modal-type="record-type-edit" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
										  	htmlspecialchars(json_encode(["type_name" => $row["type_name"]])) ?>"><i
											class="fa-regular fa-pen-to-square"></i>Edit</button>

									<button type="button" class="open-button danger" data-target="#record-type-delete"
										data-modal-type="record-type-delete" data-entity-id="<?= $row["id"] ?>">
										<i class="fa-regular fa-trash-can"></i>Delete
									</button>
								</td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td class="type"><?= htmlspecialchars($row["type_name"]) ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
</div>