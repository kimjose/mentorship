<?php

use Umb\Mentorship\Models\Team;
use Umb\Mentorship\Models\User;

$id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $t = Team::findOrFail($id);
}
$users = User::all();
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body p-2">
            <form action="" id="manage_team">

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
<script>
    const id = '<?php echo $id ?>'
    const formManageTeam = document.querySelector('#manage_team')
    const inputName = document.querySelector('#inputName')
    const selectTeamLead = document.querySelector('#selectTeamLead')

    $(document).ready(function() {
        $('.select2').select2()
    })

    $('#manage_team').submit(e => {
        e.preventDefault()
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
</script>