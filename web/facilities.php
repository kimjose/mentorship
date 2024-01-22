<?php

/** @var Umb\EventsManager\Models\User $currUser */
?>
<?php

use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\Program;
use Umb\Mentorship\Models\Team;

/*** @var \Umb\EventsManager\Models\Facility[] $facilities */
$facilities = DB::select("select f.*,p.name program, c.name 'county', t.name 'team_name', (select count(fv.facility_id) from facility_visits fv where fv.facility_id = f.id group by fv.facility_id ) as visits from facilities f left join counties c on c.code = f.county_code left join teams t on t.id = f.team_id left join programs p on p.id = f.program_id");
$activeBadge = "<span class='badge badge-primary rounded-pill'>Active</span>";
$inActiveBadge = "<span class='badge badge-warning rounded-pill'>In Active</span>";
$teams = Team::all();
$programs =  Program::all(); // TODO filter depending on the user logged in.
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Facilities </li>
    </ol>

</div>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button class="btn btn-primary ml-auto float-right btn-icon-split" data-toggle="modal" data-target="#modalFacility" id="btnAddUser">
            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
            <span class="text"> Add Facility</span>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableFacilities">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Program</th>
                        <th>Name</th>
                        <th>MFL Code</th>
                        <th>County</th>
                        <th>Team</th>
                        <th>Status</th>
                        <th>Visits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Program</th>
                        <th>Name</th>
                        <th>MFL Code</th>
                        <th>County</th>
                        <th>Team</th>
                        <th>Status</th>
                        <th>Visits</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($facilities as $facility) :
                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $facility->program ?></td>
                            <td><?php echo $facility->name ?></td>
                            <td><?php echo $facility->mfl_code  ?></td>
                            <td><?php echo $facility->county ?></php>
                            </td>
                            <td><?php echo $facility->team_name ?></php>
                            </td>
                            <td><?php echo $facility->active ? $activeBadge : $inActiveBadge ?></td>
                            <td><?php echo $facility->visits ?? 0 ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="Edit Facility" onclick='editFacility(<?php echo json_encode($facility); ?>)' data-toggle="modal" data-target="#modalFacility">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-flat delete_survey" data-tooltip="tooltip" title="Delete Facility" data-id="<?php echo $facility->id ?>" onclick='deleteFacility(<?php echo json_encode($facility); ?>)'>
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
<div class="modal fade" id="modalFacility" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Facility Dialog</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" onsubmit="event.preventDefault();" id="formFacility">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="selectProgram">Program</label>
                        <select name="program_id" id="selectProgram" class="form-control select2" required>
                            <option value="" hidden selected>Select Program</option>
                            <?php
                            for ($j = 0; $j < sizeof($programs); $j++) :
                                $program = $programs[$j];
                            ?>
                                <option value="<?php echo $program->id ?>"><?php echo $program->name; ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputName">Facility Name</label>
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="Enter facility name" required>
                    </div>

                    <div class="form-group">
                        <label for="inputMflCode">MFL Code</label>
                        <input type="number" class="form-control" id="inputMflCode" name="mfl_code" maxlength="6" placeholder="mfl code" required>
                    </div>
                    <div class="form-group">
                        <label for="selectCounty">County</label>
                        <select name="county_code" id="selectCounty" class="form-control select2">
                            <option value="" hidden selected>Select County</option>
                            <?php

                            $counties = \Umb\Mentorship\Models\County::all();
                            for ($j = 0; $j < sizeof($counties); $j++) :
                                $county = $counties[$j];
                            ?>
                                <option value="<?php echo $county->code ?>"><?php echo $county->name; ?> </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="selectTeam">Team</label>
                        <select name="team_id" id="selectTeam" class="form-control select2">
                            <option value="" hidden selected>Select Team</option>
                            <?php foreach ($teams as $team) : ?>
                                <option value="<?php echo $team->id ?>"> <?php echo $team->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row pl-2">
                        <h5>Location</h5>

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
                        <div class="col-auto">
                            <button class="btn btn-info btn-sm col-auto ml-3" onclick="currentLocation()">
                                <span class="icon"><i class="fa fa-map-pin"></i> </span>
                                <span class="text-dark">Current Location</span></button>
                            <small class="text-danger" id="sLocation"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="checkActive">
                        <label for="checkActive"> Active </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="savebtn" id="btnSaveFacility" class="btn btn-primary" onclick="saveFacility()">Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Facility Dialog end-->

<script type="text/javascript">
    const inputName = document.querySelector("#inputName");
    const inputMflCode = document.querySelector("#inputMflCode")
    const selectProgram = document.querySelector("#selectProgram")
    const selectCounty = document.querySelector("#selectCounty")
    const selectTeam = document.querySelector("#selectTeam")
    const checkActive = document.querySelector("#checkActive")
    const inputLat = document.querySelector("#inputLat")
    const inputLong = document.querySelector("#inputLong")
    const sLocation = document.querySelector("#sLocation")
    let editedId = "";

    $(document).ready(function() {
        $('#tableFacilities').dataTable()
        $('.select2').select2()
    })

    function initialize() {
        $("#modalFacility").on("hide.bs.modal", () => {
            editedId = ''
            document.querySelector("#formFacility").reset()
            $('.select2').select2()
        });
    }

    function editFacility(facility) {
        editedId = facility.id;
        inputName.value = facility.name
        inputMflCode.value = facility.mfl_code
        inputLat.value = facility.latitude
        inputLong.value = facility.longitude
        $(selectProgram).val(facility.program_id)
        $(selectCounty).val(facility.county_code)
        $(selectTeam).val(facility.team_id)
        checkActive.checked = facility.active

        $('.select2').select2()
    }

    function saveFacility() {
        let btnSaveFacility = document.getElementById('btnSaveFacility')
        let program = $(selectProgram).val()
        let name = inputName.value.trim();
        let mflCode = inputMflCode.value.trim();
        let county = $(selectCounty).val()
        let latitude = inputLat.value.trim();
        let longitude = inputLong.value.trim()
        let active = checkActive.checked
        let team = $(selectTeam).val()
        if (program === '') {
            selectProgram.focus()
            return;
        }
        if (name === '') {
            inputUsername.focus()
            return
        }
        if (county === '') {
            selectCounty.focus()
            return;
        }
        if (mflCode.length < 5) {
            inputMflCode.focus()
            return
        }

        //['username', 'email', 'phone_number', 'first_name', 'last_name', 'active', 'password']
        let data = {
            program_id: program,
            name: name,
            county_code: county,
            mfl_code: mflCode,
            latitude: latitude,
            longitude: longitude,
            team_id: team,
            active: active ? 1 : 0
        }
        let saveUrl = '../api/facility'
        let updateUrl = '../api/facility/' + editedId
        btnSaveFacility.setAttribute('disabled', '')
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
                    toastr.success("facility saved successfully.")
                    window.location.reload()
                } else throw new Error(response.message)
                // hideModal(dialogId)
            })
            .catch(error => {
                btnSaveFacility.removeAttribute('disabled')
                console.log(error.message);
                toastr.error(error.message)
            })

    }


    function currentLocation() {
        toastr.info("Getting Location...")
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, error => {
                toastr.error(error.message)
            });
        } else {
            sLocation.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        inputLat.value = position.coords.latitude
        inputLong.value = position.coords.longitude
    }


    function deleteFacility(user) {
        // TODO
    }

    initialize()
</script>