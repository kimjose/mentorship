<?php

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
        <form action="" id="formAssessmentQuetions" onsubmit="event.preventDefault()">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="section_id" value="<?php echo $section->id ?>">
            <h5>Questions</h5>
            <?php foreach ($questions as $question) : ?>
                <h5><?php echo $question->question ?></h5>
                <div class="callout callout-info">
                    <div class="form-group">
                        <textarea name="answer[<?php echo $question->id ?>]" id="" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."> <?php echo $response == null ? '' : $response->answer ?></textarea>
                    </div>
                </div>

            <?php endforeach; ?>
            <hr>
            <div class="d-flex w-100 justify-content-center">
                <input id="submitResponse" class="btn btn-sm btn-flat bg-gradient-primary mx-1" form="formFillSection" type="submit" value="Submit Answers">
                <a href="index?page=visits-open&id=<?php echo $visitId ?>" class="btn btn-sm btn-flat bg-gradient-secondary mx-1">Cancel</a>
            </div>
        </form>
    </div>
</div>