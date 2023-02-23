<?php

use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\Question;
use Umb\Mentorship\Models\VisitSection;

if (!isset($_GET['visit']) || !isset($_GET['section'])) :
?>
    <script>
        window.location.replace('index?page=visits')
    </script>
<?php endif;
$visitId = $_GET['visit'];
$sectionId = $_GET['section'];
$vs = VisitSection::where('visit_id', $visitId)->where('section_id', $sectionId)->first();

if ($vs == null || $vs->user_id != $currUser->id) :
?>
    <script>
        window.location.replace('index?page=visits')
    </script>
<?php endif;
$section = Section::findOrFail($sectionId);
$questions = Question::where('section_id', $sectionId)->get();
?>

<div class="card card-outline border-left-primary">
    <div class="card-header bg-secondary">
        <h3 class="card-title"> <?php echo $section->title ?> </h3>
    </div>
    <div class="card-body">
        <form action="" id="formFillSection">
            <input type="hidden" name="visit_id" value="<?php echo $visitId ?>">
            <input type="hidden" name="section_id" value="<?php echo $sectionId ?>">
            <?php foreach ($questions as $question) :
            ?>
                <div class="callout callout-info">
						<input type="hidden" name="qid[<?php echo $question->id ?>]" value="<?php echo $question->id ?>">	
						<input type="hidden" name="type[<?php echo $question->id ?>]" value="<?php echo $question->type ?>">	
                    <h5><?php echo $question->question ?></h5>
                    <?php
                    if ($question->type == 'textfield_s') : ?>
                        <div class="form-group">
                            <textarea name="answer[<?php echo $question->id ?>]" id="" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."></textarea>
                        </div>
                        <?php elseif ($question->type == 'radio_opt') :
                        foreach (json_decode($question->frm_option) as $k => $v) : ?>
                            <div class="icheck-primary">
                                <input type="radio" id="option_<?php echo $k ?>" name="answer[<?php echo $question->id ?>]" value="<?php echo $k ?>" checked="">
                                <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                            </div>
                        <?php endforeach;
                    elseif ($question->type == 'check_opt') :
                        foreach (json_decode($question->frm_option) as $k => $v) :
                        ?>
                            <div class="icheck-primary">
                                <input type="checkbox" id="option_<?php echo $k ?>" name="answer[<?php echo $question->id ?>][]" value="<?php echo $k ?>">
                                <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                            </div>
                    <?php endforeach;
                    endif; ?>

                </div>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex w-100 justify-content-center">
                <input class="btn btn-sm btn-flat bg-gradient-primary mx-1" form="formFillSection" type="submit" value="Submit Answers">
                <a href="index?page=visits-open&id=<?php echo $visitId ?>" class="btn btn-sm btn-flat bg-gradient-secondary mx-1">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    console.log("Here we are...");
    const visitId = '<?php echo $visitId ?>'
    const formFillSection = document.getElementById('formFillSection')
    $('#formFillSection').submit((e) => {
        e.preventDefault()
        start_load()
        $.ajax({
            url: '../api/response/submit',
            method: 'POST',
            data: new FormData(formFillSection),
            cache: false,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.code == 200) {
                    alert_toast("Thank You.", 'success')
                    setTimeout(function() {
                        location.href = `index?page=visits-open&id=${visitId}`
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