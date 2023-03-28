<?php

use Umb\Mentorship\Models\UserCategory;
use Umb\Mentorship\Models\UserPermission;

$categories = UserCategory::all();
$permissions = UserPermission::all();
?>

<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<button class="btn btn-block btn-sm btn-default btn-flat border-primary" data-toggle="modal" data-target="#modalUserCategory"><i class="fa fa-plus"></i> Add New Category</button>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Name</th>
						<th>Description</th>
						<th>Access Level</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($categories as $category) :
					?>
						<tr>
							<th class="text-center"><?php echo $i++ ?></th>
							<td><b><?php echo ucwords($category->name) ?></b></td>
							<td><b><?php echo $category->description ?></b></td>
							<td><b><?php echo $category->access_level ?></b></td>
							<td class="text-center">
								
								
								<button data-tooltip="tooltip" title="Edit Category" class="btn btn-light btn-circle btn-sm" onclick='editUserCategory(<?php echo json_encode($category); ?>)' data-toggle="modal" data-target="#modalUserCategory">
                                    <i class="fa fa-edit text-primary"></i></button>
                                <button class="btn btn-light btn-circle btn-sm" data-tooltip="tooltip" title="Delete User" onclick='deleteUser(<?php echo json_encode($user); ?>)'><i class="text-danger fa fa-trash"></i></button>

							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Categories Dialog -->
<div class="modal fade" id="modalUserCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">User Category Dialog</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<form action="" method="POST" onsubmit="event.preventDefault();" id="formUserCategory">

				<div class="modal-body">

					<div class="form-group">
						<label for="inputName">Name</label>
						<input type="text" class="form-control" id="inputName" name="name" placeholder="Enter categories name" required>
					</div>

					<div class="form-group">
						<label for="inputDescription">Description</label>
						<input type="number" class="form-control" id="inputDescription" name="description" maxlength="6" placeholder="Category Description" required>
					</div>
					<div class="form-group">
						<label for="">Access Level</label>
						<select name="access_level" id="selectAccessLevel" class="form-control">
							<option value="" selected hidden>Select Access Level</option>
							<option value="program">Program</option>
							<option value="facility">Facility</option>
						</select>
					</div>

					<hr>

					<h6>Permissions</h6>
					<div id="divPermissions">
						<?php foreach ($permissions as $permission) : ?>
							<div class="">
								<input id="<?php echo 'checkbox' . $permission->id ?>" type="checkbox" value="<?php echo $permission->name ?>" data-id="<?php echo $permission->id ?>">
								<label for="<?php echo 'checkbox' . $permission->id ?>"><?php echo $permission->name ?></label>
							</div>
						<?php endforeach; ?>
					</div>
					<hr>


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" name="savebtn" id="btnSaveCategory" class="btn btn-primary" onclick="saveUserCategory()">Save
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Categories Dialog end-->

<script>
	const formUserCategory = document.getElementById('formUserCategory')
	const inputName = document.getElementById('inputName')
	const inputDescription = document.getElementById('inputDescription')
	const selectAccessLevel = document.getElementById('selectAccessLevel')
	const divPermissions = document.getElementById('divPermissions')
	const btnSaveCategory = document.getElementById('btnSaveCategory')

	let editedId = ''

	function initialize() {
		$("#modalUserCategory").on("hide.bs.modal", () => {
			editedId = ''
			document.querySelector("#formUserCategory").reset()
		});

	}

	function editUserCategory(category){
		editedId = category.id
		let checkBoxes = divPermissions.querySelectorAll('input[type=checkbox]')
        for (let i = 0; i < checkBoxes.length; i++) {
            let checkBox = checkBoxes[i]
            let dataId = checkBox.getAttribute('data-id')
            if (category.permissions.split(",").includes(dataId)) checkBox.checked = true
        }

	}

	function saveUserCategory() {

	}

	initialize()
</script>
