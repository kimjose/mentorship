<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Umb\Mentorship\Models\TeamMember;
use Illuminate\Database\Capsule\Manager as DB;

$teamId = $_GET['team_id'];
$programId = $_GET['program_id'];
$members = TeamMember::where('team_id', $teamId)->get(['user_id'])->pluck('user_id')->toArray();
$query = "select * from users where $programId in program_ids and id not in (" . implode(",", $members) . ")";
$users = DB::select($query);

?>

<div class="container-fluid">
    <form action="" id="formAddMembers">
        <input type="hidden" name="team_id" value="<?php echo $teamId ?>">
        <div class="form-group">
            <label for="selectMembers">Select Mambers</label>
            <select name="user_ids[]" class="select2" multiple="multiple" data-placeholder="Select members" id="selectMembers">
                <?php foreach ($users as $user) : ?>
                    <option value="<?php echo $user->id ?>"><?php echo ucwords($user->first_name, ' ', $user->last_name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<script>

$(function() {
        $('.select2').select2()

        $('#formAddMembers').submit(e => {
            e.preventDefault()
            let formAddMembers = document.getElementById('formAddMembers');
            
            let formData = new FormData(formAddMembers);
            start_load()

            $.ajax({
                url: '../api/add_members_to_team',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    end_load()
                    if (resp.code == 200) {
                        alert_toast('Data successfully saved.', "success");
                        setTimeout(function() {
                           $('#uni_modal').modal('hide');
                           window.location.reload();
                        }, 800)
                    } else {
                        toastr.error(resp.message)
                    }
                },
                error: function(request, status, error) {
                    end_load()
                    alert(request.responseText);
                }
            })

        })
    })

</script>
