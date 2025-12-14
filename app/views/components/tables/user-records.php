<div class="card">
	<div class="top">
		<h2 class="header"></i>User Roles</h2>
		<div class="right actions">
			<button type="button" class="open-button" data-target="#create-user-role" data-modal-type="create-user-role">
				<i class="fa-solid fa-user-tag"></i>Add User Role
			</button>
		</div>
	</div>

	<?php if (empty($user_roles["data"])): ?>
		<p>No user roles found.</p>
	<?php else: ?>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>DAY</th>
						<th rowspan="2">A.M.</th>
						<th rowspan="2">P.M.</th>
						<th rowspan="2">UNDERTIME</th>
					</tr>
				</thead>

				<thead>
					<tr>
						<th></th>
						<th>ARRIVAL</th>
						<th>DEPARTURE</th>
						<th>ARRIVAL</th>
						<th>DEPARTURE</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$days = 31;
					$day = 1;
					foreach ($records["data"] as $row): ?>
						<tr>
							<td><?= $day ?></td>
							<td><?= htmlspecialchars($row["id"]) ?></td>
							<td><?= htmlspecialchars($row["role_name_formatted"]) ?></td>
						</tr>
						<?php
						$day++;
						if ($day > $days)
							break;
					endforeach ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>