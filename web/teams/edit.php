<?php

use Umb\Mentorship\Models\Team;
use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Program;
use Umb\Mentorship\Models\Facility;

$id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $t = Team::findOrFail($id);
}
$users = User::all();
$programs =  Program::all(); // TODO filter depending on the user logged in.
if (!hasPermission(PERM_USER_MANAGEMENT, $currUser)) :
?>
    <script>
        window.location.replace("index")
    </script>
<?php endif; ?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body p-2">
            <form action="" id="manage_team">
                <div class="form-group">
                    <label for="selectProgram">Program</label>
                    <select name="program_id" id="selectProgram" class="form-control select2" required>
                        <option value="" hidden selected>Select Program</option>
                        <?php
                        for ($j = 0; $j < sizeof($programs); $j++) :
                            $program = $programs[$j];
                        ?>
                            <option value="<?php echo $program->id ?>" <?php echo ($id != '' && $t->program_id == $program->id) ? 'selected' : '' ?> ><?php echo $program->name; ?> </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputName">Team Name</label>
                    <input placeholder="Team name" type="text" name="name" id="inputName" class="form-control" required value="<?php echo $id == '' ? '' : $t->name ?>">
                </div>
                <div class="form-group">
                    <label for="">Team Leader</label>
                    <select name="team_lead" id="selectTeamLead" required class="select2 form-control">
                        <option value="" <?php echo $id == '' ? 'selected' : '' ?> hidden>Select Lead</option>
                        <?php foreach ($users as $user) : ?>
                            <option value="<?php echo $user->id ?>" <?php echo ($id != '' && $user->id == $t->team_lead) ? 'selected' : '' ?>> <?php echo $user->getNames(); ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2" id="btnSave">Save</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = 'index?page=teams'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if ($id != '') : ?>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link  active" id="tabSections" data-toggle="tab" href="#tabContentSections" role="tab" aria-controls="tabContentVisit" aria-selected="true">Facilities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabChartAbstractions" data-toggle="tab" href="#tabContentChartAbstractions" role="tab" aria-controls="#tabContentChartAbstractions" aria-selected="false">Team Members</a>
                </li>


            </ul>

            <div class="tab-content" id="tabContentTeam">
                <div class="tab-pane fade show active" id="tabContentSections" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                    <div class="card-header">
                        <div class="card-tools">
                            <button id="btnAddFacilities" class="btn btn-block btn-sm btn-default btn-flat border-primary"><i class="fa fa-plus"></i> Add Facilities</button>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Facility Name</th>
                                <th>MFL Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $facilities = Facility::where('team_id', $id)->get();
                            foreach ($facilities as $facility) :
                            ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $facility->name ?></td>
                                    <td><?php echo $facility->mfl_code ?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-flat" data-tooltip="tooltip" title="Remove from team" data-id="<?php echo $facility->id ?>" onclick='removeFacility(<?php echo $facility->id; ?>)'>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                                $i++;
                            endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Facility Name</th>
                                <th>MFL Code</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    const id = '<?php echo $id ?>'
    const formManageTeam = document.querySelector('#manage_team')
    const inputName = document.querySelector('#inputName')
    const selectProgram = document.querySelector('#selectProgram')
    const selectTeamLead = document.querySelector('#selectTeamLead')

    $(document).ready(function() {
        $('.select2').select2()
        $('#btnAddFacilities').click(() => {
            uni_modal("Add Facilities", `teams/dialog_add_facilities?team_id=${id}`, "large")
        })
    })

    $('#manage_team').submit(e => {
        e.preventDefault()
        let program = $(selectProgram).val()
        if (program === '') {
            selectProgram.focus();
            toastr.error('Select program first')
            return
        }
        let name = inputName.value.trim()
        if (name === '') {
            inputName.focus()
            return
        }
        let lead = $(selectTeamLead).val()
        if (lead === '') {
            selectTeamLead.focus()
            return
        }
        let formData = new FormData(formManageTeam);

        fetch(id != '' ? `../api/team/${id}` : '../api/team', {
                method: 'POST',
                headers: {
                    "content-type": "application/x-www-form-urlencoded"
                },
                body: JSON.stringify({
                    program_id: program,
                    name: name,
                    team_lead: lead
                })
            })
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    toastr.success(response.message)
                    setTimeout(() => window.location.replace('index?page=teams'), 890)
                } else throw new Error(response.message)
            })
            .catch(err => {
                toastr.error(err.message)
            })
    })

    function removeFacility(facilityId) {
        customConfirm("Confirm Action", "Are you sure you want to remove this facility from the team?", () => {
            fetch('../api/remove_facility_from_team', {
                    method: 'POST',
                    headers: {
                        "content-type": "application/x-www-form-urlencoded"
                    },
                    body: JSON.stringify({
                        'facility_id': facilityId,
                        'team_id': id
                    })
                })
                .then(response => {
                    return response.json();
                })
                .then(response => {
                    if (response.code === 200) {
                        toastr.success(response.message);
                        setTimeout(() => window.location.reload(), 969)
                    } else throw new Error(response.message)
                })
                .catch(err => {
                    toastr.error(err.message)
                })
        }, () => {})

    }
</script>