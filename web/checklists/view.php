<?php

use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\Checklist;

$id = $_GET['id'] ?? '';
if ($id != '') {
	$checklist = Checklist::find($id);
	if ($checklist == null) $id = '';
}
$sections = Section::where('checklist_id', $id)->get();

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

		<div class="row m-4">
			<h3>Sections</h3>
			<button class="btn btn-primary btn-icon-split ml-auto float-right" data-toggle="modal" data-target="#modalSection">
				<span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
				<span class="text"> Add Section</span>
			</button>
		</div>

	</div>

	<div class="body">
		<?php foreach ($sections as $section) : ?>
			<div class="card shadow mb-4">
				<a href="#collapseCard_<?php echo $section->id ?>" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCard">
					<h6 class="m-0 font-weight-bold text-primary text-center"><?php echo $section->title ?> - <small><?php echo $section->abbr ?></small></h6>
				</a>

				<div class="collapse hide" id="collapseCard_<?php echo $section->id ?>">
					<div class="card-body">


						<div class="row p-2">
							<h4>Questions</h4>
							<button class="btn btn-primary btn-icon-split ml-auto float-right" data-toggle="modal" data-target="#modalSection">
								<span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
								<span class="text"> Add Question</span>
							</button>
						</div>
						<hr>
						<div class="row justify-content-center">
							<div class="col-auto">

							</div>
						</div>
						<hr>
					</div>
				</div>
				<div>
					<div class="btn-group float-right">
						<button class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modalSection" title="Edit Section" onclick=' editSection(<?php echo $section->id ?>, "<?php echo $section->title ?>", "<?php echo $section->abbr ?>")'>
							<i class="fas fa-edit"></i>
						</button>
						<button type="button" class="btn btn-danger btn-flat delete_survey" data-id="<?php echo $section->id ?>" title="Delete Section">
							<i class="fas fa-trash"></i>
						</button>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<?php include_once "dialog_add_section.php" ?>

<script>


</script>