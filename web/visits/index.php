<?php

/** @var Umb\Mentorship\Models\User $currUser */

use Umb\Mentorship\Models\FacilityVisit;


$visits = FacilityVisit::all();
$activeBadge = "<span class='badge badge-primary rounded-pill'>Active</span>";
$inActiveBadge = "<span class='badge badge-warning rounded-pill'>In Active</span>";
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button class="btn btn-primary ml-auto float-right btn-icon-split" data-toggle="modal" data-target="#modalVisit" id="btnAddVisit">
            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
            <span class="text"> New Visit</span>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableVisits">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Facility</th>
                        <th>Date</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Facility</th>
                        <th>Date</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($visits as $visit) :
                        $creator = $visit->getCreator();
                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $visit->getFacility()->name ?></td>
                            <td><?php echo $visit->visit_date  ?></td>
                            <td><?php echo $creator->first_name . ' ' . $crearor->last_name ?></php>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="Edit Visit" onclick='editVisit(<?php echo json_encode($visit); ?>)' data-toggle="modal" data-target="#modalVisit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-flat delete_visit" data-tooltip="tooltip" title="Delete Visit" data-id="<?php echo $visit->id ?>" onclick='deleteVisit(<?php echo json_encode($visit); ?>)'>
                                        <i class="fas fa-trash"></i>
                                    </button>
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
<div class="modal fade" id="modalVisit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Facility Visit Dialog</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" onsubmit="event.preventDefault();" id="formVisit">

                <div class="modal-body">

                    <div class="form-group">
                        <label for="selectFacility">Facility</label>
                        <select name="county_code" id="selectFacility" class="form-control select2">
                            <option value="" hidden disabled selected>Select Facility</option>
                            <?php

                            $facilities = \Umb\Mentorship\Models\Facility::all();
                            for ($j = 0; $j < sizeof($facilities); $j++) :
                                $facility = $facilities[$j];
                            ?>
                                <option value="<?php echo $facility->id ?>"><?php echo $facility->name; ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputVisitDate">Visit Date</label>
                        <input type="date" class="form-control" id="inputVisitDate" name="visit_date" placeholder="Visit Date" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="savebtn" id="btnSaveVisit" class="btn btn-primary" onclick="saveVisit()">Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Facility Dialog end-->

<script type="text/javascript">
    const selectFacility = document.querySelector("#selectFacility");
    const inputVisitDate = document.querySelector("#inputVisitDate")
    let editedId = "";

    $(document).ready(function() {
        $('#tableVisits').dataTable()
        $('.select2').select2()
    })

    function initialize() {
        $("#modalVisit").on("hide.bs.modal", () => {
            editedId = ''
            document.querySelector("#formVisit").reset()
            $(selectFacility).select2()
        });
    }

    function editVisit(visit) {
        console.log('Here we are.....');
        editedId = visit.id;
        inputVisitDate.value = visit.visit_date
        $(selectFacility).val(visit.facility_id)
        $(selectFacility).select2()
    }

    function saveVisit() {
        let btnSaveVisit = document.getElementById('btnSaveVisit')
        let visitDate = inputVisitDate.value
        let facility = $(selectFacility).val()
        if (visitDate === '') {
            inputVisitDate.focus()
            return
        }
        if (facility === '') {
            selectFacility.focus()
            return;
        }
        //['username', 'email', 'phone_number', 'first_name', 'last_name', 'active', 'password']
        let data = {
            visit_date: visitDate,
            facility_id: facility
        }
        let saveUrl = '../api/visit'
        let updateUrl = `../api/visit/${editedId}`
        btnSaveVisit.setAttribute('disabled', '')
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
                    toastr.success("Visit saved successfully.")
                    window.location.reload()
                } else throw new Error(response.message)

            })
            .catch(error => {
                btnSaveVisit.removeAttribute('disabled')
                console.log(error.message);
                toastr.error(error.message)
            })

    }

    function deleteVisit(visit) {
        // TODO
    }

    initialize()
</script>