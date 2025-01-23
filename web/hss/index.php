<?php

/** @var Umb\Mentorship\Models\User $currUser */


$assessments = [];
$assessments = \Umb\Mentorship\Models\HssAssessment::all();
$counties = \Umb\Mentorship\Models\County::all();
$todaysDate = date("Y-m-d");

$SubmittedBadge = "<span class='badge badge-success rounded-pill'>Submitted</span>";
$draftBadge = "<span class='badge badge-warning rounded-pill'>Draft</span>";
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Hss Assessments </li>
    </ol>

</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button class="btn btn-primary ml-auto float-right btn-icon-split" data-toggle="modal" data-target="#modalAssessment" id="btnAddAssessment">
            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
            <span class="text"> New Assessment</span>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableAssessments">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>County</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                <tr>
                        <th>#</th>
                        <th>County</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($assessments as $assessment) :
                        $creator = $assessment->user;
                        $section = $assessment->section;
                        $county = $assessment->county;
                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $county->name ?></td>
                            <td><?php echo $section->name ?></td>
                            <td><?php echo $assessment->assessment_date  ?></td>
                            <td><?php echo $assessment->status == 'submitted' ? $SubmittedBadge : $draftBadge ?></td>
                            </td>
                            <td><?php echo $creator->first_name . ' ' . $creator->last_name ?></php>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="index?page=hss-open&id=<?php echo $assessment->id ?>" class="btn btn-secondary btn-flat" data-tooltip="tooltip" title="View Assessment">
                                        <i class="fas fa-fw fa-sign-in-alt"></i>
                                    </a>
                                   
                                </div>
                            </td>
                        </tr>

                    <?php $i++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Facility Dialog -->
<div class="modal fade" id="modalAssessment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Assessment Dialog</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" onsubmit="event.preventDefault();" id="formAssessment">

                <div class="modal-body">

                    <div class="form-group">
                        <label for="selectCounty">County</label>
                        <select name="county_code" id="selectCounty" class="form-control select2">
                            <option value="" hidden disabled selected>Select County</option>
                            <?php

                            for ($j = 0; $j < sizeof($counties); $j++) :
                                $county = $counties[$j];
                            ?>
                                <option value="<?php echo $county->code ?>"><?php echo $county->name; ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputAssessmentDate">Assessment Date</label>
                        <input type="date" class="form-control" id="inputAssessmentDate" name="assessment_date" max="<?php echo $todaysDate ?>" placeholder="Assessment Date" required>
                    </div>
                    <div class="form-group">
                        <label for="selectSection">Section</label>
                        <select name="section_id" id="selectSection" class="form-control select2">
                            <?php
                            $sections = \Umb\Mentorship\Models\HssSection::all();
                            for ($j = 0; $j < sizeof($sections); $j++) :
                                $section = $sections[$j];
                            ?>
                                <option value="<?php echo $section->id ?>"><?php echo $section->name; ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="savebtn" id="btnSaveAssessment" class="btn btn-primary" onclick="saveAssessment()">Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Facility Dialog end-->

<script type="text/javascript">
    const selectCounty = document.querySelector("#selectCounty");
    const inputAssessmentDate = document.querySelector("#inputAssessmentDate")
    let editedId = "";
    const selectSection = document.querySelector("#selectSection")

    $(document).ready(function() {
        $('#tableAssessments').dataTable()
        $('.select2').select2()
    })

    function initialize() {
        $("#modalAssessments").on("hide.bs.modal", () => {
            editedId = ''
            document.querySelector("#formAssessments").reset()
            $(selectFacility).select2()
        });
    }

    // function editVisit(visit) {
    //     console.log('Here we are.....');
    //     editedId = visit.id;
    //     inputVisitDate.value = visit.visit_date
    //     $(selectFacility).val(visit.facility_id)
    //     $(selectFacility).select2()
    // }

    function saveAssessment() {
        let btnSaveAssessment = document.getElementById('btnSaveAssessment')
        let assessmentDate = inputAssessmentDate.value
        let county = $(selectCounty).val()
        let section = $(selectSection).val()
        if (assessmentDate === '') {
            inputAssessmentDate.focus()
            return
        }
        if (county === '') {
            selectCounty.focus()
            return;
        }
        if (section === '') {
            selectSection.focus()
            return;
        }
        let data = {
            assessment_date: assessmentDate,
            county_code: county,
            section_id: section
        }
        let saveUrl = '../api/hss_assessment'
        let updateUrl = `../api/hss_assessment/${editedId}`
        btnSaveAssessment.setAttribute('disabled', '')
        fetch(
                editedId === "" ? saveUrl : updateUrl, {

                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        "content-type": "application/x-www-form-urlencoded"
                    }
                }
            )
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    toastr.success("Assessment saved successfully.")
                    $assessment = response.data
                    window.location.replace(`index?page=hss-open&id=${$assessment.id}`)
                } else throw new Error(response.message)

            })
            .catch(error => {
                btnSaveAssessment.removeAttribute('disabled')
                console.log(error.message);
                toastr.error(error.message)
            })

    }



    initialize()
</script>