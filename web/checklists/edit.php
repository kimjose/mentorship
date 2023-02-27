<?php

use Umb\Mentorship\Models\CheckList;

$id = "";
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$checklist = Checklist::find($id);
	if ($checklist == null) $id = '';
}
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
		<li class="breadcrumb-item">
            <a href="index?page=checklists">Checklists</a>
        </li>
        <li class="breadcrumb-item active"> Edit </li>
    </ol>

</div>

<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="formChecklist" onsubmit="event.preventDefault()">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Title</label>
							<input type="text" name="title" class="form-control form-control-sm" required value="<?php echo $id != '' ? $checklist->title : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Start</label>
							<input type="date" name="start_date" class="form-control form-control-sm" required value="<?php echo isset($start_date) ? $start_date : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">End</label>
							<input type="date" name="end_date" class="form-control form-control-sm" required value="<?php echo isset($end_date) ? $end_date : '' ?>">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Abbreviation</label>
							<input type="text" name="abbr" class="form-control form-control-sm" required value="<?php echo $id != '' ? $checklist->abbr : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">Description</label>
							<textarea name="description" id="" cols="30" rows="4" class="form-control" required><?php echo $id != '' ? $checklist->description : '' ?></textarea>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button id="btnSaveChecklist" class="btn btn-primary mr-2" onclick="saveChecklist()">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index?page=checklist'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	const formChecklist = document.getElementById('formChecklist')
	const btnSaveChecklist = document.getElementById('btnSaveChecklist')
	const id = "<?php echo $id ?>"

	function saveChecklist() {
		let formData = new FormData(formChecklist)
		let checklist = {};
		for (let [key, value] of formData.entries()) {
			checklist[key] = value
		}

		fetch(id == '' ? `../api/checklist` : `../api/checklist/${id}`, {
				method: "POST",
				body: JSON.stringify(checklist),
				headers: {
					"content-type": "application/x-www-form-urlencoded"
				}
			})
			.then(response => {
				return response.json()
			})
			.then(response => {
				btnSaveChecklist.removeAttribute('disabled')
				if (response.code === 200) {
					window.location.replace("index?page=checklists")
				} else throw new Error(response.message)
				// hideModal(dialogId)
			})
			.catch(error => {
				if (btnSaveChecklist.hasAttribute('disabled')) btnSaveChecklist.removeAttribute('disabled')
				console.log(error.message);
				toastr.error(error.message)
			})

	}
</script>
