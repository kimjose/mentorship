<?php

use Umb\Mentorship\Models\Team;

$teams = Team::all();

?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index?page=teams-edit"><i class="fa fa-plus"></i> Add Team</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Name</th>
						<th>Team Lead</th>
						<th>Facilities</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($teams as $team) :
					?>
						<tr>
							<th class="text-center"><?php echo $i++ ?></th>
							<td><b><?php echo ucwords($team->name) ?></b></td>
							<td><b><?php echo ucwords($team->lead()->getNames())  ?></b></td>
							<td><b><?php echo sizeof($team->facilities()) ?></b></td>
							<td class="text-center">
								<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
									Action
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="./index?page=teams-edit&id=<?php echo $team->id ?>">Edit</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_team" href="javascript:void(0)" data-id="<?php echo $team->id ?>">Delete</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#list').dataTable()
		$('.delete_team').click(function() {
			_conf("Are you sure to delete this user?", "delete_team", [$(this).attr('data-id')])
		})
	})

	function delete_team($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_team',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>