<?php

/** @var Umb\Mentorship\Models\User $currUser */

use Umb\Mentorship\Models\FacilityVisit;


$visits = [];
if ($currUser->getCategory()->access_level == 'Facility') {
	$visits = FacilityVisit::where('facility_id', $currUser->facility_id)->get();
} else {
	$visits = FacilityVisit::all();
}
$activeBadge = "<span class='badge badge-primary rounded-pill'>Active</span>";
$inActiveBadge = "<span class='badge badge-warning rounded-pill'>In Active</span>";
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Visits </li>
    </ol>

</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <?php if (hasPermission(PERM_CREATE_VISIT, $currUser)) : ?>
            <button class="btn btn-primary ml-auto float-right btn-icon-split" data-toggle="modal" data-target="#modalVisit" id="btnAddVisit">
                <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
                <span class="text"> New Visit</span>
            </button>
        <?php endif; ?>
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
                            <td><?php echo $creator->first_name . ' ' . $creator->last_name ?></php>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="index?page=visits-open&id=<?php echo $visit->id ?>" class="btn btn-secondary btn-flat" data-tooltip="tooltip" title="Open Visit">
                                        <i class="fas fa-fw fa-sign-in-alt"></i>
                                    </a>
                                    <?php if (hasPermission(PERM_CREATE_VISIT, $currUser)) : ?>
                                        <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="Edit Visit" onclick='editVisit(<?php echo json_encode($visit); ?>)' data-toggle="modal" data-target="#modalVisit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-flat delete_visit" data-tooltip="tooltip" title="Delete Visit" data-id="<?php echo $visit->id ?>" onclick='deleteVisit(<?php echo json_encode($visit); ?>)'>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
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
                    <div class="row pl-2">
                        <h5>Location</h5>
                        <button class="btn btn-info btn-sm col-auto ml-3" onclick="verifyLocation()"><span class="text-dark">Verify Location</span></button>
                        <small class="text-danger" id="sLocation"></small>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="inputLat">Latitude</label>
                                <input type="number" step="0.00001" class="form-control" min="-90" max="90" id="inputLat" name="latitude">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="inputLong">Longitude</label>
                                <input class="form-control" type="number" step="0.00001" min="-180" max="180" id="inputLong" name="longitude">
                            </div>
                        </div>

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
    const inputLat = document.querySelector("#inputLat")
    const inputLong = document.querySelector("#inputLong")
    const sLocation = document.querySelector("#sLocation")

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
        let latitude = inputLat.value.trim();
        let longitude = inputLong.value.trim()
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
            facility_id: facility,
            latitude: latitude,
            longitude: longitude
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

    function verifyLocation() {
        toastr.info("Getting Location...")
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, (error) => {
                toastr.error(error.message)
                inputLat.value = 0.00
                inputLong.value = 0.00
            }, {
                timeout: 10000
            });
        } else {
            sLocation.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        inputLat.value = position.coords.latitude
        inputLong.value = position.coords.longitude
        inputDistance.value = getDistanceFromCoordinates([position.coords.latitude, position.coords.longitude], [venue.latitude, venue.longitude]).toFixed(2)
    }

    function deleteVisit(visit) {
        // TODO
    }

    initialize()
</script>
