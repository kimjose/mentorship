<?php

use Umb\Mentorship\Models\ActionPoint;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\VisitFinding;
use Umb\Mentorship\Models\VisitSection;

if (!isset($_GET['id']) || !hasPermission(PERM_CREATE_VISIT, $currUser)) :
?>
    <script>
        window.location.replace('index?page=visits');
    </script>
<?php endif; ?>

<?php
$id = $_GET['id'];
/** @var FacilityVisit $visit */
$visit = FacilityVisit::find($id);
if ($visit == null) return;
$visitSections = VisitSection::where('visit_id', $id)->get();
$checklists = Checklist::where('status', 'published')->get();
/** @var Umb\Mentorship\Models\VisitFinding[] */
$findings = VisitFinding::where('visit_id', $id)->get();


$openedBadge = "<span class='badge badge-primary rounded-pill'>Draft</span>";
$notOpenedBadge = "<span class='badge badge-warning rounded-pill'>Not Opened</span>";
$submittedBadge = "<span class='badge badge-success rounded-pill'>Submitted</span>";
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="index?page=visits">Visits</a>
        </li>
        <li class="breadcrumb-item active"> Open </li>
    </ol>

</div>
<div class="card-body shadow m-2">

    <h4 style="margin-left: 10px;">Visit Details</h4>
    <div class="row p-2">
        <div class="col-md-6 col-sm-12 mb-1" style="border-left: solid 3px #000FAD; border-radius:3px">
            <h6 class="text-secondary text-bold">Facility</h6>
            <p class="text-primary"><?php echo $visit->getFacility()->name ?></p>
        </div>
        <div class="col-md-6 col-sm-12" style="border-left: solid 3px #000FAD; border-radius:3px">
            <h6 class="text-secondary text-bold">Date</h6>
            <p class="text-primary"><?php echo $visit->visit_date ?></p>
        </div>
    </div>

</div>


<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link  active" id="tabFindings" data-toggle="tab" href="#tabContentFindings" role="tab" aria-controls="tabContentVisit" aria-selected="true">Findings/Summary</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabSections" data-toggle="tab" href="#tabContentSections" role="tab" aria-controls="tabContentVisit" aria-selected="true">Checklists</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabChartAbstractions" data-toggle="tab" href="#tabContentChartAbstractions" role="tab" aria-controls="#tabContentChartAbstractions" aria-selected="false">Chart Abstraction</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabActionPoints" data-toggle="tab" href="#tabContentActionPoints" role="tab" aria-controls="#tabContentActionPoints" aria-selected="false">Action Points</a>
            </li>

        </ul>
        <div class="tab-content" id="tabContentVisit">
            <!-- Tab content summary -->
            <div class="tab-pane fade show active" id="tabContentFindings" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">


                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-6">
                            <h5>Findings</h5>
                        </div>
                        <button class="btn btn-primary ml-auto float-right btn-icon-split" id="btnAddFinding" onclick="newFinding()">
                            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
                            <span class="text"> New Finding</span>
                        </button>
                    </div>
                </div>
                <ul id="listFindings" style="list-style: lower-greek;">
                    <?php foreach ($findings as $finding) : ?>
                        <li>
                            <div class="card shadow h-100 py-2 mt-2">
                                <div style="float:right">

                                    <div class="btn-group " style="float: right;">
                                        <button class="btn btn-info btn-flat" data-tooltip="tooltip" title="Add Action Point">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-flat" data-tooltip="tooltip" title="Edit Finding" onclick="editFinding(<?php echo $finding->id ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-flat delete_visit" data-tooltip="tooltip" title="Delete Finding">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <h6 class="ml-2 text-info"><?php $finding->createdBy()->getNames(); ?></h6>
                                </div>
                                <div class="card-body">
                                    <p> <?php echo $finding->description ?> </p>
                                    <div>
                                        <h6 class="action-points"> <span> 3</span> Action point(s) </h6>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>

            <div class="tab-pane fade show " id="tabContentSections" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                <h3>Checklists</h3>

                <?php foreach ($checklists as $checklist) :
                    $sections = Section::where('checklist_id', $checklist->id)->get();
                ?>
                    <div class="card shadow mb-4">
                        <!-- Card Header - Accordion -->
                        <a href="#collapseCardChecklist<?php echo $checklist->id ?>" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                            <h6 class="m-0 font-weight-bold text-primary text-center"> <abbr title="<?php echo $checklist->title ?>"> <?php echo $checklist->abbr ?> </abbr> </h6>
                        </a>
                        <!-- Card Content - Collapse -->
                        <div class="collapse hide" id="collapseCardChecklist<?php echo $checklist->id ?>">
                            <div class="card-body">
                                <div class="col-auto table-responsive">
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Abbreviation</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sections as $section) : ?>
                                                <tr>
                                                    <td></td>
                                                    <td><?php echo $section->title ?></td>
                                                    <td><?php echo $section->abbr ?></td>
                                                    <td>
                                                        <?php
                                                        $openedSection = VisitSection::where('visit_id', $id)->where('section_id', $section->id)->first();
                                                        if ($openedSection == null) {
                                                            echo $notOpenedBadge;
                                                        } else {
                                                            echo ($openedSection->submitted ? $submittedBadge : $openedBadge) . "<br>";
                                                            $opener = User::findOrFail($openedSection->user_id);
                                                            echo $opener->first_name . " " . $opener->last_name;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <?php if ($openedSection == null || !$openedSection->submitted) : ?>
                                                                <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="Edit Section" onclick='openSection("<?php echo $section->id; ?>")'>
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            <?php else : ?>
                                                                <button class="btn btn-secondary btn-flat" data-tooltip="tooltip" title="View Response" onclick='viewResponse(<?php echo $section->id; ?>)'>
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>


            </div>
            <!-- Tab Chart Abstraction -->
            <div class="tab-pane fade show" id="tabContentChartAbstractions" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                <div class="table-responsive">
                    <!-- Dynamic table can be long based on user data elements
                             of interest (artstartdate, TPT,VL,CACX,NCDs,weight,height etc.) -->
                    <table class="table table-striped">
                        <thead>
                            <th>#</th>
                            <th>CCCNumber</th>
                            <th>Section</th>
                            <th>AgeGroup</th>
                            <th>Gaps Identified</th>
                            <th>Assigned To</th>
                            <th>Reviewed By</th>
                        </thead>

                        <tbody>
                            <?php
                            /** @var ActionPoint[] $aps */
                            $aps = ActionPoint::where('visit_id', $visit->id)->get();
                            foreach ($aps as $ap) :
                            ?> //
                                <tr>
                                    <td><?php echo $c ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Chart Abstraction end -->
            <!-- Tab Action points -->
            <div class="tab-pane fade show" id="tabContentActionPoints" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <th>Checklist</th>
                            <th>Section</th>
                            <th>Question</th>
                            <th>Action Point</th>
                            <th>Description</th>
                            <th>Assigned To</th>
                            <th>Created By</th>
                        </thead>

                        <tbody>
                            <?php
                            /** @var ActionPoint[] $aps */
                            $aps = ActionPoint::where('visit_id', $visit->id)->get();
                            foreach ($aps as $ap) :
                            ?>
                                <tr>
                                    <td><?php echo $ap->question()->section()->checklist()->title ?></td>
                                    <td><?php echo $ap->question()->section()->title ?></td>
                                    <td><?php echo $ap->question()->question ?></td>
                                    <td><?php echo $ap->title ?></td>
                                    <td><?php echo $ap->description ?></td>
                                    <td></td>
                                    <td><?php echo $ap->creator()->first_name . ' ' . $ap->creator()->last_name ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Action points end -->
        </div>
    </div>
</div>

<!-- Response Dialog -->
<div class="modal fade" id="modalViewResponses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Response</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped" id="tableResponsive">
                    <thead>
                        <th>Question</th>
                        <th>Response</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Section Dialog end-->

<style>
    #listFindings li {
        border-left: #000FAD 10px !important;
    }

    .action-points {
        color: #000FAD;
    }

    .action-points:hover {
        cursor: pointer;
        text-decoration-style: dotted;
    }
</style>

<script>
    const visitId = '<?php echo $id ?>'
    const tableResponsive = document.getElementById('tableResponsive')

    $(function() {

    })

    function viewResponse(sectionId) {
        view_modal("View Response", `visits/dialog_view_response.php?section_id=${sectionId}&visit_id=${visitId}`, "large")
    }

    function newFinding() {
        uni_modal("Add Finding", `visits/dialog_create_finding?visit_id=${visitId}`, "large")
    }

    function editFinding(findingId) {
        uni_modal("Add Finding", `visits/dialog_create_finding?visit_id=${visitId}&finding_id=${findingId}`, "large")
    }

    function openSection(sectionId) {
        let data = {
            section_id: sectionId,
            visit_id: visitId
        }
        fetch('../api/open_visit_section', {
                method: 'POST',
                body: JSON.stringify(data)
            })
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    window.location.replace(`index?page=visits-_section&visit=${visitId}&section=${sectionId}`)
                } else {
                    throw new Error(response.message)
                }
            })
            .catch(err => {
                toastr.error(err.message)
            })
    }
</script>