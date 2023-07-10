<?php
require_once __DIR__ . "/../../vendor/autoload.php";
$baseUrl = $_ENV['APP_URL'];

use Umb\Mentorship\Models\ActionPoint;
?>
<section class="" id="sectionVisitSummary">

    <div class="header">

        <div class="row">
            <div class="col-3">
                <img class="logo" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="MOH Logo" srcset="">
            </div>
            <div class="col-6">
                <h5 class="text-center">TA Visit Summary</h5>
                <h5 class="text-center">Facility Name: <span class="text-primary"> Embakasi HC</span></h5>
                <h5 class="text-center">Visit Date: <span class="text-primary"> 2023-07-06 </span> </h5>
            </div>
            <div class="col-3">
                <img class="logo" src="<?php echo $baseUrl ?>web/assets/img/visit.png" alt="CIHEB Logo" srcset="">
            </div>
        </div>
    </div>
    <hr>
    <div class="body">
        <div id="divChecklists">
            <h4>Program/Areas Supervised</h4>
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
                <h4>Key Findings</h4>
                <div class="mt-3">
                    <ol>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        <li>
                            <div width="100%" class="divListItem">
                                .
                            </div>
                        </li>
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

        <!-- Section Action points summary-->
        <div class="tab-pane fade show" id="divSummaryActionPoint" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
            <div class="table-responsive">
                <h4>Recommendation: Action Points</h4>
                <table class="table table-striped">
                    <thead>
                        <th>Action Point</th>
                        <th>Description</th>
                        <th>By Who</th>
                        <th>By When</th>
                        <th>Level:National Regional/Facility</th>
                    </thead>

                    <tbody>
                        <?php
                        /** @var ActionPoint[] $aps */
                        $aps = ActionPoint::where('visit_id', $visit->id)->get();
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
                <h4>Supervision Team</h4>
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
                <h4>Facility Incharge</h4>
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
    <button class="btn btn-success m-2 btn-icon-split" id="btnPdfParticipants" onclick="">
        <span class="icon"><i class="fa fa-file-pdf" aria-hidden="true"></i></span>
        <span class="text">Export to pdf</span>
    </button>
</section>

<style>
    #sectionVisitSummary {
        padding: 8px;
        background-color: FFFEF2 !important;
        background-blend-mode: luminosity;
        margin: 10px;
    }

    .logo {
        height: 80px;
        width: 80px;
    }

    #divChecklists ol,
    #divSummaryFindings ol {
        list-style: disc;
    }

    #divChecklists .divListItem,
    #divSummaryFindings .divListItem {
        border-bottom: #000F00 1px solid;
    }
</style>