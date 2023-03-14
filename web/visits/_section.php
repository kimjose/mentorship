<?php

use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\Question;
use Umb\Mentorship\Models\Response;
use Umb\Mentorship\Models\VisitSection;
use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\FacilityVisit;

if (!isset($_GET['visit']) || !isset($_GET['section'])) :
?>
    <script>
        window.location.replace('index?page=visits')
    </script>
<?php endif;
$visitId = $_GET['visit'];
$sectionId = $_GET['section'];
$visit = FacilityVisit::find($visitId);
$vs = VisitSection::where('visit_id', $visitId)->where('section_id', $sectionId)->first();
$visitDate = $visit->visit_date;
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
        <form action="" id="formFillSection" onsubmit="event.preventDefault()">
            <input type="hidden" name="visit_id" value="<?php echo $visitId ?>">
            <input type="hidden" name="section_id" value="<?php echo $sectionId ?>">
            <?php foreach ($questions as $question) :
                $response = Response::where('question_id', $question->id)->where('visit_id', $visitId)->first();
                $query = "select r.*, fv.visit_date from responses r left join facility_visits fv on fv.id = r.visit_id where question_id = {$question->id} and visit_date < '{$visit->visit_date}' order by visit_date desc";
                // echo $query;
                $prevResponses = DB::select($query);
                $prevResponse = $prevResponses[0];
            ?>
                <div class="callout callout-info">
                    <input type="hidden" name="qid[<?php echo $question->id ?>]" value="<?php echo $question->id ?>">
                    <input type="hidden" name="type[<?php echo $question->id ?>]" value="<?php echo $question->type ?>">
                    <h5><?php echo $question->question ?></h5>
                    <?php
                    if ($question->type == 'textfield_s') :
                        if ($prevResponse) $prevResponse->answer_value = $prevResponse->answer;
                    ?>
                        <div class="form-group">
                            <textarea name="answer[<?php echo $question->id ?>]" id="" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."> <?php echo $response == null ? '' : $response->answer ?></textarea>
                        </div>
                        <?php elseif ($question->type == 'radio_opt') :
                        foreach (json_decode($question->frm_option) as $k => $v) :
                            if ($prevResponse && $prevResponse->answer = $k) $prevResponse->answer_value = $v;
                        ?>
                            <div class="icheck-primary">
                                <input type="radio" id="option_<?php echo $k ?>" name="answer[<?php echo $question->id ?>]" value="<?php echo $k ?>" <?php
                                                                                                                                                        if ($response != null) {
                                                                                                                                                            $ans = explode(',', $response->answer);
                                                                                                                                                            if (in_array($k, $ans)) echo 'checked';
                                                                                                                                                        }
                                                                                                                                                        ?>>
                                <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                            </div>
                        <?php endforeach;
                    elseif ($question->type == 'check_opt') :
                        $ansValues = [];
                        $ansKeys = [];
                        if ($prevResponse) $ansKeys = explode(',', $prevResponse->answer);
                        foreach (json_decode($question->frm_option) as $k => $v) :
                            if (in_array($k, $ansKeys)) $ansValues[] = $v;
                        ?>
                            <div class="icheck-primary">
                                <input type="checkbox" id="option_<?php echo $k ?>" name="answer[<?php echo $question->id ?>][]" value="<?php echo $k ?>" <?php
                                                                                                                                                            if ($response != null) {
                                                                                                                                                                $ans = explode(',', $response->answer);
                                                                                                                                                                if (in_array($k, $ans)) echo 'checked';
                                                                                                                                                            }
                                                                                                                                                            ?>>
                                <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                            </div>
                        <?php
                            if ($prevResponse) $prevResponse->answer_value = implode(',', $ansValues);
                        endforeach;
                    elseif ($question->type = "number_opt") :
                        if ($prevResponse) $prevResponse->answer_value = $prevResponse->answer;
                        ?>
                        <div class="form-group">
                            <input type="number" name="answer[<?php echo $question->id ?>]" id="" class="form-control">
                        </div>
                    <?php
                    endif; ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-9 col-sm-10">
                            <p class="text-primary"><?php echo $prevResponse ? $prevResponse->answer_value : '' ?></p>
                            <b><?php echo $prevResponse ? $prevResponse->visit_date : '' ?></b>

                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="d-flex flex-column align-items-end">
                                <button class="btn btn-primary" onclick="addActionPoint(<?php echo $question->id ?>)">
                                    <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
                                    <span class="text"> Add Action Point</span>
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex w-100 justify-content-center">
                <input id="saveDraft" class="btn btn-sm btn-flat bg-gradient-primary mx-1" form="formFillSection" type="submit" value="Save Draft">
                <input id="submitResponse" class="btn btn-sm btn-flat bg-gradient-primary mx-1" form="formFillSection" type="submit" value="Submit Answers">
                <a href="index?page=visits-open&id=<?php echo $visitId ?>" class="btn btn-sm btn-flat bg-gradient-secondary mx-1">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    console.log("Here we are...");
    const visitId = '<?php echo $visitId ?>'
    const formFillSection = document.getElementById('formFillSection')

    $('#saveDraft').click(() => {
        // e.preventDefault()
        start_load()
        $.ajax({
            url: '../api/response/save_draft',
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

    $('#submitResponse').click(() => {
        // e.preventDefault()
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

    function addActionPoint(questionId) {
        console.log('Bang...' + visitId + ' qn ' + questionId);
        uni_modal("New Action Point", `visits/dialog_create_action_point.php?question_id=${questionId}&visit_id=${visitId}`, "large")
    }
</script>