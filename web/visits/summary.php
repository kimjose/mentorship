<?php
require_once __DIR__ . "/../../vendor/autoload.php";
$baseUrl = $_ENV['APP_URL'];

use Umb\Mentorship\Models\ActionPoint;
use Umb\Mentorship\Models\ChartAbstraction;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\Response;
use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\VisitFinding;
use Illuminate\Database\Capsule\Manager as DB;

$visitId = $_GET['visit_id'];
/** @var FacilityVisit $visit */
$visit = FacilityVisit::find($visitId);
$facility = $visit->getFacility();
/** @var VisitFinding[]  $findings */
$findings = VisitFinding::where('visit_id', $visitId)->get();
$userIds = [];
$responseUsers = Response::where('visit_id', $visitId)->get(['created_by']);
foreach ($responseUsers as $responseUser) {
    array_push($userIds, $responseUser['created_by']);
}
$abstractionUsers = ChartAbstraction::where('visit_id', $visitId)->get(['created_by']);
foreach ($abstractionUsers as $abstractionUser) {
    array_push($userIds, $abstractionUser['created_by']);
}
$findingUsers = VisitFinding::where('visit_id', $visitId)->get(['created_by']);
foreach ($findingUsers as $findingUser) {
    array_push($userIds, $findingUser['created_by']);
}
$users = User::whereIn('id', $userIds)->get();
$checklists = DB::select("SELECT DISTINCT(c.id), c.title from responses r left join questions q on q.id = r.question_id left join sections s on s.id = q.section_id left join checklists c on c.id = s.checklist_id where visit_id = {$visitId};");

?>
<div>
    <section class="" id="sectionVisitSummary">

        <div class="header">

            <div class="row">
                <div class="col-3">
                    <img src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="MOH Logo" style="width: 80px; height: 80px" srcset="">
                </div>
                <div class="col-6">
                    <h3 class="text-center">TA Visit Summary</h3>
                    <h4 class="text-center"><?php echo $facility->name ?></h4>
                    <h4 class="text-center"><span class="text-primary"> <?php echo $visit->visit_date ?> </span> </h4>
                </div>
                <div class="col-3">
                    <img style="width: 80px; height: 80px" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="CIHEB Logo" srcset="">
                </div>
            </div>
        </div>
        <hr>
        <div class="body">
            <div id="divProgramAreas">
                <h4 class="section-header">Program/Areas Supervised</h4>
                <div class="row mt-3">
                    <div class="col-6">
                        <ol style="list-style: none;">
                            <?php for ($i = 0; $i < 4; $i++) : ?>
                                <li>
                                    <div width="100%" class="divListItem">
                                        <?php
                                        $checklist = $checklists[$i];
                                        echo $checklist ? $checklist->title : '.'; ?>
                                    </div>
                                </li>
                            <?php endfor; ?>

                        </ol>
                    </div>
                    <div class="col-6">
                        <ol style="list-style: none;">
                            <?php for ($i = 4; $i < 8; $i++) : ?>
                                <li>
                                    <div width="100%" class="divListItem">
                                        <?php
                                        $checklist = $checklists[$i];
                                        echo $checklist ? $checklist->title : '.'; ?>
                                    </div>
                                </li>
                            <?php endfor; ?>
                          
                        </ol>
                    </div>
                </div>
            </div>

            <div id="divSummaryFindings">
                <div>
                    <h4 class="section-header">Key Findings</h4>
                    <div class="mt-3">
                        <ol>
                            <?php foreach ($findings as $finding) : ?>
                                <li>
                                    <div width="100%" class="divListItem">
                                        <?php echo $finding->description; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>

                        </ol>
                    </div>
                </div>
            </div>

            <!--Visit Action points summary-->
            <div class="tab-pane fade show" id="divSummaryActionPoint" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                <div class="table-responsive">
                    <h4 class="section-header">Recommendation: Action Points</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>Action Point</th>
                            <th>Description</th>
                            <th>Assigned Person<sub>s</sub></th>
                            <th>By Who</th>
                            <th>By When</th>
                        </thead>

                        <tbody>
                            <?php
                            /** @var ActionPoint[] $aps */
                            $aps = ActionPoint::where('visit_id', $visitId)->get();
                            foreach ($aps as $ap) :
                                $assigned = $ap->assignedTo();
                            ?>
                                <tr>
                                    <td><?php echo $ap->title ?></td>
                                    <td><?php echo $ap->description ?></td>
                                    <td>
                                        <ul class="list-inline">
                                            <?php foreach ($assigned as $u) :
                                            ?>
                                                <li class="list-inline-item assigned-tag"> <?php echo $u->getNames(); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <td><?php echo $ap->creator()->first_name . ' ' . $ap->creator()->last_name ?></td>
                                    <td><?php echo $ap->due_date ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Section Action points summary end -->

            <!-- Section Supervision Team summary-->
            <div class="tab-pane fade show" id="divSummarySupervisionTeam" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                <div class="table-responsive">
                    <h4 class="section-header">Supervision Team</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                        </thead>

                        <tbody>
                            <?php
                            /** @var ActionPoint[] $aps */
                            $aps = ActionPoint::where('visit_id', $visit->id)->get();
                            foreach ($users as $user) :
                                $assigned = $ap->assignedTo();
                            ?>
                                <tr>
                                    <td><?php echo $user->getNames(); ?></td>
                                    <td><?php echo $user->phone_number ?></td>
                                    <td><?php echo $user->email ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Section Supervision Team summary end -->

            <div id="divSummaryFacilityIncharge">
                <div>
                    <h4 class="section-header">Facility Incharge</h4>
                    <div class="mt-3">
                        <ol>

                            <div width="50%" class="divListItem">
                                Name of Facility Incharge:
                            </div>

                            <div width="50%" class="divListItem">
                                Designation:
                            </div>
                            <div width="50%" class="divListItem">
                                <span>Date:</span>
                            </div>

                        </ol>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <button class="btn btn-success m-2 btn-icon-split" id="btnPdfSummary" onclick="printPdfSummary()">
        <span class="icon"><i class="fa fa-file-pdf" aria-hidden="true"></i></span>
        <span class="text">Export to pdf</span>
    </button>

</div>

<style>
    #sectionVisitSummary {
        padding: 28px;
        background-color: FFFEF2 !important;
        background-blend-mode: luminosity;
        margin: 10px;
        border: #000F00 solid 1px;
    }

    .logo {
        height: 80px;
        width: 80px;
    }

    .section-header {
        color: #0047AB;
    }

    #divProgramAreas {
        margin-bottom: 10px;
    }

    #divProgramAreas ol,
    #divSummaryFindings ol {
        list-style: disc;
    }

    #divProgramAreas .divListItem,
    #divSummaryFindings .divListItem {
        border-bottom: #000F00 1px solid;
    }
</style>