<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Checklist Report </li>
    </ol>

</div>
<?php
# Get facilities 
# Get checklists
# Hapo pengine bado sijajua

use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\Facility;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\Response;

$facilities = Facility::all();
/** @var Checklist[] */
$checklists = Checklist::where('status', 'NOT LIKE', 'draft')->get();

$submit = '';
if (isset($_GET['submit'])) {
    $submit = $_GET['submit'];
    extract($_GET);
}

?>

<div class="card">
    <div class="card-header">
        <form action="index" method="get">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <input type="hidden" name="page" value="checklist_report">
                    <div class="form-group">
                        <label for="selectFacility">Facility</label>
                        <select name="facility_id" id="selectFacility" required class="form-control select2">
                            <option value="" hidden <?php echo !$submit ? 'selected' : '' ?>>Select facility</option>
                            <?php foreach ($facilities as $facility) : ?>
                                <option value="<?php echo $facility->id ?>" <?php echo ($submit && $facility_id == $facility->id) ? 'selected' : '' ?>> <?php echo $facility->name ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="">Checklist</label>
                        <select name="checklist_id" id="selectChecklist" class="form-control select2">
                            <option value="" hidden <?php echo !$submit ? 'selected' : '' ?>>Select Checklist</option>
                            <?php foreach ($checklists as $checklist) : ?>
                                <option value="<?php echo $checklist->id ?>" <?php echo ($submit && $checklist_id == $checklist->id) ? 'selected' : '' ?>> <?php echo $checklist->title ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="inputStartDate">Start Date</label>
                        <input type="date" name="start_date" id="inputStartDate" class="form-control" required value="<?php echo $submit ? $start_date : '' ?>">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="inputEndDate">End Date</label>
                        <input type="date" name="end_date" id="inputEndDate" class="form-control" required value="<?php echo $submit ? $end_date : '' ?>">
                    </div>
                </div>
            </div>
            <input class="btn-sm btn-block btn-wave col-md-4 btn-primary" type="submit" value="Get Report" name="submit">
        </form>
    </div>
    <div class="card-body">
        <div class="justify-content-center">
            <?php
            if ($submit) :
                /** @var Checklist */
                $checklist = Checklist::findOrFail($checklist_id);
                $sections = $checklist->getSections();
                /** @var FacilityVisit[] */
                $visits = FacilityVisit::where('facility_id', $facility_id)->where('visit_date', '>=', $start_date)->where('visit_date', '<=', $end_date)->orderBy('visit_date', 'asc')->get();
                
            ?>
                <ul class="nav nav-tabs" role="tablist">
                    <?php
                    $c = 0;
                    foreach ($sections as $section) :
                        $questions = $section->getQuestions();
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $c == 0 ? 'active' : '' ?>" id="tab<?php echo $section->id ?>" data-toggle="tab" href="#tabContent<?php echo $section->id ?>" role="tab" aria-controls="#tabContentSections" aria-selected="true">
                                <abbr title="<?php echo $section->title ?>"><?php echo $section->abbr ?></abbr>
                            </a>
                        </li>
                    <?php
                        $c++;
                    endforeach; ?>
                </ul>
                <div class="tab-content" id="tabContentSections">
                    <?php
                    $d = 0;
                    foreach ($sections as $section) :
                        $questions = $section->getQuestions();
                    ?>
                        <div class="tab-pane fade p-2 show <?php echo $d == 0 ? 'active' : '' ?>" id="tabContent<?php echo $section->id ?>" role="tabpanel" aria-labelledby="no-label">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" rowspan="2">Questions</th>
                                            <th class="text-center" colspan="<?php echo (sizeof($visits) > 0) ? sizeof($visits) : 1 ?>">Visits & Response</th>
                                        </tr>
                                        <tr>
                                            <?php foreach ($visits as $visit) : ?>
                                                <th><?php echo $visit->visit_date ?></th>
                                            <?php endforeach ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($questions as $question) : ?>
                                            <tr>
                                                <td class="text-center"><?php echo $question->question ?></td>
                                                <?php foreach ($visits as $visit) :
                                                    $answer = '';
                                                    $response = Response::where('visit_id', $visit->id)->where('question_id', $question->id)->first();
                                                    if ($response != null) {
                                                        if ($question->type == 'textfield_s' || $question->type == 'number_opt')
                                                            $answer = $response->answer;
                                                        elseif ($question->type == 'check_opt') {
                                                            $options = json_decode($question->frm_option);
                                                            $ansArray = explode(',', $response->answer);
                                                            $selected = [];
                                                            foreach ($ansArray as $a) {
                                                                $selected[] = $options->$a;
                                                            }
                                                            $answer = implode(',', $selected);
                                                        } elseif ($question->type == 'radio_opt') {
                                                            $options = json_decode($question->frm_option);
                                                            $a = $response->answer;
                                                            $answer = $options->$a;
                                                        }
                                                    }
                                                ?>
                                                    <td class="text-center"><?php echo $answer ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php $d++;
                    endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>