<div class="card">
	<div class="top">
		<h2 class="header">Record Types</h2>
		<a href="all-events" target="_blank"><i class="fa-solid fa-print"></i>Print to PDF</a>
	</div>
	<?php if (!empty($records)): ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>Actions</th>
						<th>ID</th>
						<th>Type Name</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($record_types as $row): ?>
						<tr>
							<td class="actions">
								<button type="button" class="open-button" data-target="#edit-record-type"
									data-modal-type="edit-record-type" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
									  	htmlspecialchars(json_encode(["type_name" => $row["type_name"]])) ?>"><i
										class="fa-regular fa-pen-to-square"></i>Edit</button>

								<form action="delete-record-type" method="post" class="action">
									<input type="hidden" name="row-id" value="<?= $row["id"] ?>">
									<button class="danger" type="submit"
										onclick="return confirm('Are you sure you want to delete the current record type <?= $row['type_name_formatted'] ?>?')"><i
											class="fa-regular fa-trash-can"></i>Delete</button>
								</form>
							</td>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td class="type"><?= htmlspecialchars($row["type_name_formatted"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>No records found.</p>
	<?php endif; ?>
</div>