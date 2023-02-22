<?php

/** @var Umb\EventsManager\Models\User $currUser */
if (!in_array($currUser->category_id, [1])) : ?>
    <script>
        window.location.replace('index.php');
    </script>
<?php endif; ?>
<?php
/*** @var \Umb\EventsManager\Models\Facility[] $facilities */
$facilities = \Umb\Mentorship\Models\Facility::where('id', '>', 1)->get();
$activeBadge = "<span class='badge badge-primary rounded-pill'>Active</span>";
$inActiveBadge = "<span class='badge badge-warning rounded-pill'>In Active</span>";
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
                        <th>Name</th>
                        <th>MFL Code</th>
                        <th>County</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>MFL Code</th>
                        <th>County</th>
                        <th>Status</th>
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
                            <td><?php echo $facility->name ?></td>
                            <td><?php echo $facility->mfl_code  ?></td>
                            <td><?php echo $facility->getCounty()->name ?></php>
                            </td>
                            <td><?php echo $facility->active ? $activeBadge : $inActiveBadge ?></td>
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
                        <label for="inputName">Facility Name</label>
                        <input type="text" class="form-control" id="inputName" name="name" placeholder="Enter facility name" required>
                    </div>

                    <div class="form-group">
                        <label for="inputMflCode">MFL Code</label>
                        <input type="number" class="form-control" id="inputMflCode" name="mfl_code" maxlength="6" placeholder="mfl code" required>
                    </div>
                    <div class="form-group">
                        <label for="selectCounty">County</label>
                        <select name="county_code" id="selectCounty" class="form-control">
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
    const selectCounty = document.querySelector("#selectCounty")
    const checkActive = document.querySelector("#checkActive")
    let editedId = "";

    $(document).ready(function() {
        $('#tableFacilities').dataTable()
    })

    function initialize() {
        $("#modalFacility").on("hide.bs.modal", () => {
            editedId = ''
            document.querySelector("#formFacility").reset()
        });
    }

    function editFacility(facility) {
        editedId = facility.id;
        inputName.value = facility.name
        inputMflCode.value = facility.mfl_code
        $(selectCounty).val(facility.county_code)
        checkActive.checked = facility.active
    }

    function saveFacility() {
        let btnSaveFacility = document.getElementById('btnSaveFacility')
        let name = inputName.value.trim();
        let mflCode = inputMflCode.value.trim();
        let county = $(selectCounty).val()
        let active = checkActive.checked
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
            name: name,
            county_code: county,
            mfl_code: mflCode,
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

    function deleteFacility(user) {
        // TODO
    }

    initialize()
</script>