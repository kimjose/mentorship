<?php

use Umb\Mentorship\Models\Checklist;

$id = $_GET['id'] ?? '';
if ($id != '') {
	$checklist = Checklist::find($id);
	if ($checklist == null) $id = '';
}
if ($id == '') :
?>
	<script>
		window.location.replace("index?page=checklists");
	</script>
<?php
endif;
?>
<script>
	const checklistId = "<?php echo $id ?>"
</script>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="formChecklist" onsubmit="event.preventDefault()">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Title</label>
							<input type="text" name="title" class="form-control form-control-sm" required value="<?php echo $id != '' ? $checklist->title : ''  ?> readonly">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Abbreviation</label>
							<input type="text" name="abbr" class="form-control form-control-sm" required value="<?php echo $id != '' ? $checklist->abbr : '' ?> readonly">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<section>
	<div class="head">
		<h4>Sections</h4>
		<button class="btn btn-primary btn-icon-split ml-auto float-right" data-toggle="modal" data-target="#modalSection">
			<span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
			<span class="text"> Add Section</span>
		</button>
	</div>

	<div class="body">

	</div>
</section>

<?php include_once "dialog_add_section.php" ?>
