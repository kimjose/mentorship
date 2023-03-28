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

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
	<ol class="breadcrumb mb-4 transparent">
		<li class="breadcrumb-item">
			<a href="index">Home</a>
		</li>
		<li class="breadcrumb-item">
			<a href="index?page=checklists">Checklists</a>
		</li>
		<li class="breadcrumb-item active"> View </li>
	</ol>

</div>

<script>
	const checklistId = "<?php echo $id ?>"
</script>
<div class="card">

	<div class="card-header">
		<div class="col-lg-12">
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
	<div class="card-body">


		<section>
			<div class="head">

				<div class="row m-1">
					<h3>Sections</h3>

					<?php if ($checklist->status == 'draft') : ?>
						<button class="btn btn-primary btn-icon-split ml-auto float-right" data-toggle="modal" data-target="#modalSection">
							<span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
							<span class="text"> Add Section</span>
						</button>
					<?php endif; ?>
				</div>
				<p>To import the questions, add sections and upload an excel file. Use this <a href="../public/templates/questions_import_template.xlsx">template </a> to popuplate your excel.</p>

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
									<?php if ($checklist->status == 'draft') : ?>
										<button class="btn btn-primary btn-icon-split ml-auto float-right" onclick='importQuestions(<?php echo $section->id ?>)'>
											<span class="icon text-white-50"><i class="fa fa-download"></i> </span>
											<span class="text"> Import Questions</span>
										</button>
										<button class="btn btn-primary btn-icon-split ml-auto float-right" onclick='newQuestion(<?php echo $section->id ?>)'>
											<span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
											<span class="text"> Add Question</span>
										</button>
									<?php endif; ?>
								</div>
								<hr>
								<div class="justify-content-center">
									<?php
									foreach ($section->questions as $question) :

									?>
										<div class="callout callout-info">
											<div class="row">
												<?php if ($checklist->status == 'draft') : ?>
													<div class="col-md-12">
														<span class="dropleft float-right">
															<a class="fa fa-ellipsis-v text-dark" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
															<div class="dropdown-menu">
																<a class="dropdown-item edit_question text-dark" href="javascript:void(0)" data-id="<?php echo $question->id ?>" onclick="editQuestion(<?php echo $section->id . ',' . $question->id ?>)">Edit</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item delete_question text-dark" href="javascript:void(0)" data-id="<?php echo $question->id ?> " onclick="deleteQuestion(<?php echo $question->id ?>)">Delete</a>
															</div>
														</span>
													</div>
												<?php endif; ?>
											</div>
											<h5><?php echo $question->question ?> <span class='badge badge-secondary rounded-pill'><?php echo $question->frequency()->name; ?></span>
												<span class='badge badge-info rounded-pill'><?php echo $question->category; ?></span>
											</h5>

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
												<?php elseif ($question->type == 'textfield_s') : ?>
													<div class="form-group">
														<textarea name="answer[<?php echo $question->id ?>]" id="" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."></textarea>
													</div>
												<?php elseif ($question->type) : ?>
													<div class="form-group">
														<input type="number" name="answer[<?php echo $question->id ?>]" id="" class="form-control" readonly aria-readonly="true">
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
							<?php if ($checklist->status == 'draft') : ?>
								<div class="btn-group float-right">
									<button class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modalSection" title="Edit Section" onclick=' editSection(<?php echo $section->id ?>, "<?php echo $section->title ?>", "<?php echo $section->abbr ?>")'>
										<i class="fas fa-edit"></i>
									</button>
									<button type="button" class="btn btn-danger btn-flat delete_survey" data-id="<?php echo $section->id ?>" title="Delete Section">
										<i class="fas fa-trash"></i>
									</button>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

	</div>
	<div class="card-footer">
		<div class="col-auto">
			<?php if ($checklist->status == 'draft') : ?>
				<button class="btn btn-primary" id="btnPublishChecklist">
					<span class="text">Publish</span>
				</button>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php include_once "dialog_add_section.php" ?>

<script>
	function newQuestion(sectionId) {
		uni_modal("New Question", `checklists/dialog_add_question.php?section_id=${sectionId}`, "large")
	}

	function editQuestion(sectionId, id) {
		uni_modal("New Question", `checklists/dialog_add_question.php?section_id=${sectionId}&id=${id}`, "large")
	}

	function deleteQuestion(qid) {
		customConfirm("Confirm delete", "Are you sure you want to delete this question?", () => {
			fetch('../api/delete_question', {
					method: 'POST',
					body: JSON.stringify({
						id: qid
					}),
					headers: {
						"content-type": "application/x-www-form-urlencoded"
					}
				})
				.then(response => {
					return response.json();
				})
				.then(response => {
					if (response.code === 200) {
						alert_toast(response.message, "success");
						setTimeout(() => window.location.reload(), 890)
					} else throw new Error(response.message)
				})
				.catch(err => {
					toastr.error(err.message)
				})
		}, () => {
			console.log("Oooh how lucky i am..🤸..");
		})
	}

	function importQuestions(sectionId) {
		uni_modal("Import Question", `checklists/dialog_import_questions.php?section_id=${sectionId}`, "large")
	}

	const btnPublishChecklist = document.getElementById("btnPublishChecklist")
	btnPublishChecklist.addEventListener('click', () => {
		customConfirm("Confirm publish", "Once published this checklist will be available for filling in the visits but you will not be able edit it further. Do you wish to proceed?", () => {
			btnPublishChecklist.setAttribute('disabled', '')
			fetch("../api/checklist-publish", {
					method: 'POST',
					headers: {
						"content-type": "application/x-www-form-urlencoded"
					},
					body: JSON.stringify({
						id: checklistId
					})
				})
				.then(response => {
					return response.json()
				})
				.then(response => {
					let code = response.code
					if (code == 200) {
						toastr.success(response.message)
						setTimeout(() => {
							window.location.reload()
						}, 850)
					} else throw new Error(response.message)
				})
				.catch(error => {
					toastr.error(error.message)
					btnPublishChecklist.removeAttribute('disabled')
				})
		}, () => {})

	})
</script>