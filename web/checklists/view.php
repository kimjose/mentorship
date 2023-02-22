<?php

use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\Question;

$id = $_GET['id'] ?? '';
if ($id != '') {
	$checklist = Checklist::find($id);
	if ($checklist == null) $id = '';
}
$sections = Section::where('checklist_id', $id)->get();
foreach ($sections as $section) {
	$questions = Question::where('section_id', $section->id)->get();
	$section['questions'] = $questions;
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
							<button class="btn btn-primary btn-icon-split ml-auto float-right" onclick='newQuestion(<?php echo $section->id ?>)'>
								<span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
								<span class="text"> Add Question</span>
							</button>
						</div>
						<hr>
						<div class="justify-content-center">
							<?php
							foreach ($section->questions as $question) :
								
							?>
								<div class="callout callout-info">
									<div class="row">
										<div class="col-md-12">
											<span class="dropleft float-right">
												<a class="fa fa-ellipsis-v text-dark" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
												<div class="dropdown-menu" >
													<a class="dropdown-item edit_question text-dark" href="javascript:void(0)" data-id="<?php echo $question->id ?>">Edit</a>
													<div class="dropdown-divider"></div>
													<a class="dropdown-item delete_question text-dark" href="javascript:void(0)" data-id="<?php echo $question->id ?>">Delete</a>
												</div>
											</span>
										</div>
									</div>
									<h5><?php echo $question->question ?></h5>
									<div class="col-md-12">
										<input type="hidden" name="qid[]" value="<?php echo $question->id ?>">
										<?php
										if ($question->type == 'radio_opt') :
											foreach (json_decode($question->frm_option) as $k => $v) :
										?>
												<div class="icheck-primary">
													<input type="radio" id="option_<?php echo $k ?>" name="answer[<?php echo $$question->id ?>]" value="<?php echo $k ?>" checked="">
													<label for="option_<?php echo $k ?>"><?php echo $v ?></label>
												</div>
											<?php endforeach; ?>
											<?php elseif ($question->type == 'check_opt') :
											foreach (json_decode($question->frm_option) as $k => $v) :
											?>
												<div class="icheck-primary">
													<input type="checkbox" id="option_<?php echo $k ?>" name="answer[<?php echo $question->id ?>][]" value="<?php echo $k ?>">
													<label for="option_<?php echo $k ?>"><?php echo $v ?></label>
												</div>
											<?php endforeach; ?>
										<?php else : ?>
											<div class="form-group">
												<textarea name="answer[<?php echo $row['id'] ?>]" id="" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."></textarea>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
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
	function editQuestion() {

	}

	function newQuestion(sectionId) {
		uni_modal("New Question", `checklists/dialog_add_question.php?section_id=${sectionId}`, "large")
	}

	function editQuestion(sectionId, id) {
		uni_modal("New Question", `manage_question.php?section_id=${sectionId}&id=${id}`, "large")
	}
</script>