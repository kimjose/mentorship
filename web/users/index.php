<?php

use Umb\Mentorship\Models\User;

if (!hasPermission(PERM_USER_MANAGEMENT, $currUser)) :
?>
	<script>
		window.location.replace("index")
	</script>
<?php endif;

$users = [];
if ($currUser->getCategory()->access_level == 'Facility') {
	$users = User::where('facility_id', $currUser->facility_id)->get();
} else {
	$users = User::all();
}
?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index?page=users-edit"><i class="fa fa-plus"></i> Add New User</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table tabe-hover table-bordered" id="list">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Name</th>
							<th>Phone Number</th>
							<th>User category</th>
							<th>Email</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						foreach ($users as $user) :
						?>
							<tr>
								<th class="text-center"><?php echo $i++ ?></th>
								<td><b><?php echo ucwords($user->first_name . ' ' . $user->last_name) ?></b></td>
								<td><b><?php echo $user->phone_number ?></b></td>
								<td><b><?php echo $user->getCategory()->name ?></b></td>
								<td><b><?php echo $user->email ?></b></td>
								<td class="text-center">
									<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										Action
									</button>
									<div class="dropdown-menu">
										<a class="dropdown-item users-view" href="javascript:void(0)" data-id="<?php echo $user->id ?>">View</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="./index?page=users-edit&id=<?php echo $user->id ?>">Edit</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_user" href="javascript:void(0)" data-id="<?php echo $user->id ?>">Delete</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#list').dataTable()
		$('.view_user').click(function() {
			uni_modal("<i class='fa fa-id-card'></i> User Details", "users/view?id=" + $(this).attr('data-id'))
		})
		$('.delete_user').click(function() {
			_conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')])
		})
	})

	function delete_user($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_user',
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