<?php

use Umb\Mentorship\Models\HssAnswer;
use Umb\Mentorship\Models\HssAssessment;

$id = $_GET['id'];
/** @var HssAssessment $assessment */
$assessment = HssAssessment::find($id);
$section = $assessment->section;
$questions = $section->questions;

?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="index?page=hss">HSS Assessments</a>
        </li>
        <li class="breadcrumb-item active"> Open </li>
    </ol>

</div>
<div class="card card-outline border-left-primary">
    <div class="card-header bg-secondary">
        <h3 class="card-title"> <?php echo $assessment->name ?> </h3>
    </div>
    <div class="card-body">
        <form action="" id="formAssessmentQuestions" onsubmit="event.preventDefault()">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <h5>Questions</h5>
            <?php foreach ($questions as $question) :
                $answer = HssAnswer::where('assessment_id', $id)->where('question_id', $question->id)->first();
            ?>

                <div class="callout callout-info" style="padding: 4px !important;">
                    <div class="form-group">
                        <label><?php echo $question->question ?></label>
                        <textarea name="answers[<?php echo $question->id ?>]" id="" cols="30" rows="2" class="form-control" placeholder="Write Something Here..."> <?php echo $answer == null ? '' : $answer->answer ?></textarea>
                    </div>
                </div>

            <?php endforeach; ?>
            <hr>
            <?php if ($currUser->id == $assessment->user_id): ?>
                <div class="d-flex w-100 justify-content-center">
                    <input id="submitResponse" class="btn btn-sm btn-flat bg-gradient-primary mx-1" form="formAssessmentQuestions" type="submit" value="Submit Answers">
                    <a href="index?page=visits-open&id=<?php echo $visitId ?>" class="btn btn-sm btn-flat bg-gradient-secondary mx-1">Cancel</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
    const assessmentId = '<?php echo $id ?>'
    const formAssessmentQuestions = document.getElementById('formAssessmentQuestions')

    $('#submitResponse').click(() => {
        // e.preventDefault()
        start_load()
        $.ajax({
            url: `../api/hss_assessment/submit/${assessmentId}`,
            method: 'POST',
            data: new FormData(formAssessmentQuestions),
            cache: false,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.code == 200) {
                    alert_toast("Thank You.", 'success')
                    setTimeout(function() {
                        location.href = `index?page=hss-open&id=${assessmentId}`
                    }, 2000)
                } else {
                    toastr.error(resp.message)
                }
            },
            error: function(request, status, error) {
                alert(request.responseText);
            }
        })
    })
</script>