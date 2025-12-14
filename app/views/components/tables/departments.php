<div class="card">
	<div class="top">
		<h2 class="header">Departments</h2>
		<?php if (isset($isAdmin) && $isAdmin): ?>
			<div class="right actions">
				<div class="row">
					<button type="button" class="open-button" data-target="#department-create" data-modal-type="department-create">
						<i class="fa-solid fa-building"></i>Add Department
					</button>
	
					<a href="all-departments" target="_blank" class="action">
						<i class="fa-solid fa-print"></i>Print to PDF
					</a>
				</div>
			</div>
		<?php endif ?>
	</div>
	<?php if (empty($departments["data"])): ?>
		<p>No departments found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<?php if (isset($isAdmin) && $isAdmin): ?>
							<th>Actions</th>
						<?php endif ?>
						<th>ID</th>
						<th>Name</th>
						<th>Abbreviation</th>
						<th>Standard AM In</th>
						<th>Standard AM Out</th>
						<th>Standard PM In</th>
						<th>Standard PM Out</th>
						<th>Created On</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($departments["data"] as $row): ?>
						<tr>
							<?php if (isset($isAdmin) && $isAdmin): ?>
								<td class="actions">
									<button type="button" class="open-button" data-target="#department-edit"
										data-modal-type="department-edit" data-entity-id="<?= $row["id"] ?>" data-entity-data="<?=
										  	htmlspecialchars(
										  		json_encode([
										  			"department_name" => $row["department_name"],
										  			"abbreviation" => $row["abbreviation"],
										  			"standard_am_time_in" => $row["standard_am_time_in"],
										  			"standard_am_time_out" => $row["standard_am_time_out"],
										  			"standard_pm_time_in" => $row["standard_pm_time_in"],
										  			"standard_pm_time_out" => $row["standard_pm_time_out"],
										  			"created_at" => $row["created_at"],
										  		])
										  	) ?>">
										<i class="fa-regular fa-pen-to-square"></i>Edit
									</button>

									<button type="button" class="open-button danger" data-target="#department-delete"
										data-modal-type="department-delete" data-entity-id="<?= $row["id"] ?>">
										<i class="fa-regular fa-trash-can"></i>Delete
									</button>
								</td>
							<?php endif ?>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["department_name"]) ?></td>
							<td><?= htmlspecialchars($row["abbreviation"]) ?></td>
							<td><?= htmlspecialchars($row["standard_am_time_in_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["standard_am_time_out_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["standard_pm_time_in_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["standard_pm_time_out_formatted"]) ?></td>
							<td><?= htmlspecialchars($row["created_on_formatted"] . ", " . $row["created_at_formatted"]) ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>