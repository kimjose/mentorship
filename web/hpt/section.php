<?php

use Umb\Mentorship\Models\HptSection;

$id = $_GET['id'] ?? '';
if ($id != '') {
    $section = HptSection::find($id);
    if (!$section) $id = '';
}
if ($id == ''):
?>
    <script>
        window.location.replace("index?page=checklists");
    </script>
<?php
endif;
$questions = $section->questions;
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="index?page=checklists">HPT Sections </a>
        </li>
        <li class="breadcrumb-item active"> View ( <span class="text-info"><?php echo $section->name; ?></span> )</li>
    </ol>

</div>

<div class="card">
    <div class="card-body">
        <div class="head">
            <h3 class="card-title">HPT Section: <small><?php echo $section->name; ?></small></h3>
            <br>
            <p>To import the questions, add sections and upload an excel file. Use this <a href="../public/templates/questions_import_template.xlsx">template </a> to popuplate your excel.</p>
        </div>

        <hr>
        <section>
            <div class="row p-2">
                <h4>Questions</h4>
                <button class="btn btn-primary btn-icon-split ml-auto float-right" onclick='importQuestions(<?php echo $section->id ?>)'>
                    <span class="icon text-white-50"><i class="fa fa-download"></i> </span>
                    <span class="text"> Import Questions</span>
                </button>
                <button class="btn btn-primary btn-icon-split ml-auto float-right" onclick='newQuestion(<?php echo $section->id ?>)'>
                    <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
                    <span class="text"> Add Question</span>
                </button>
            </div>

            <div class="justify-content-center">
                <?php foreach ($questions as $question) : ?>
                    <div class="callout callout-info">
                        <div class="row">

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
                        </div>
                        <h5><?php echo $question->question_text ?> <span class='badge badge-secondary rounded-pill'>
                        </h5>

                        <div class="col-md-12">
                            <input type="hidden" name="qid[]" value="<?php echo $question->id ?>">
                            <?php
                            if ($question->question_type == 'radio_opt') :
                                foreach (json_decode($question->options) as $k => $v) :
                            ?>
                                    <div class="icheck-primary">
                                        <input type="radio" id="option_<?php echo $k ?>" name="answer[<?php echo $$question->id ?>]" value="<?php echo $k ?>" checked="">
                                        <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <?php elseif ($question->question_type == 'check_opt') :
                                foreach (json_decode($question->options) as $k => $v) :
                                ?>
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="option_<?php echo $k ?>" name="answer[<?php echo $question->id ?>][]" value="<?php echo $k ?>">
                                        <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif ($question->question_type == 'textfield_s') : ?>
                                <div class="form-group">
                                    <textarea name="answer[<?php echo $question->id ?>]" id="" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."></textarea>
                                </div>
                            <?php elseif ($question->question_type) : ?>
                                <div class="form-group">
                                    <input type="number" name="answer[<?php echo $question->id ?>]" id="" class="form-control" readonly aria-readonly="true">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </section>


    </div>
</div>


<script>
	function newQuestion(sectionId) {
		uni_modal("New Question", `hpt/dialog_add_question.php?section_id=${sectionId}`, "large")
	}

	function editQuestion(sectionId, id) {
		uni_modal("New Question", `hpt/dialog_add_question.php?section_id=${sectionId}&id=${id}`, "large")
	}

	function deleteQuestion(qid) {
		customConfirm("Confirm delete", "Are you sure you want to delete this question?", () => {
			fetch('../api/hpt/delete_question', {
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
		uni_modal("Import Question", `hpt/dialog_import_questions.php?section_id=${sectionId}`, "large")
	}

</script>
