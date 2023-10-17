<?php
require_once __DIR__ . "/../../vendor/autoload.php";
$baseUrl = $_ENV['APP_URL'];

use Umb\Mentorship\Models\ActionPoint;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\VisitFinding;

$visitId = $_GET['visit_id'];
/** @var FacilityVisit $visit */
$visit = FacilityVisit::find($visitId);
$facility = $visit->getFacility();
/** @var VisitFinding[]  $findings */
$findings = VisitFinding::where('visit_id', $visitId)->get();
?>
<div>
    <section class="" id="sectionVisitSummary">

        <div class="header">

            <div class="row">
                <div class="col-3">
                    <img class="logo" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="MOH Logo" srcset="">
                </div>
                <div class="col-6">
                    <h3 class="text-center">TA Visit Summary</h3>
                    <h4 class="text-center"><?php echo $facility->name ?></h4>
                    <h4 class="text-center"><span class="text-primary"> <?php echo $visit->visit_date ?> </span> </h4>
                </div>
                <div class="col-3">
                    <img class="logo" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="CIHEB Logo" srcset="">
                </div>
            </div>
        </div>
        <hr>
        <div class="body">
            <div id="divProgramAreas">
                <h4 class="section-header">Program/Areas Supervised</h4>
                <div class="row mt-3">
                    <div class="col-6">
                        <ol>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                        </ol>
                    </div>
                    <div class="col-6">
                        <ol>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
                            <li>
                                <div width="100%" class="divListItem">
                                    .
                                </div>
                            </li>
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
                    <table class="table table-striped">
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
                    <table class="table table-striped">
                        <thead>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Organization</th>
                            <th>Signature</th>
                        </thead>

                        <tbody>
                            <?php
                            /** @var ActionPoint[] $aps */
                            $aps = ActionPoint::where('visit_id', $visit->id)->get();
                            foreach ($aps as $ap) :
                                $assigned = $ap->assignedTo();
                            ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $ap->creator()->first_name . ' ' . $ap->creator()->last_name ?></td>
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

    <button class="btn btn-success m-2 btn-icon-split" id="btnPdfParticipants" onclick="">
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