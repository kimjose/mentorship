<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Umb\Mentorship\Models\Facility;

$teamId = $_GET['team_id'];
$programId = $_GET['program_id'];
$facilities = Facility::where('program_id', $programId)->where('team_id', null)->get();

?>

<div class="container-fluid">
    <form action="" id="formAddFacilities">
        <input type="hidden" name="team_id" value="<?php echo $teamId ?>">
        <div class="form-group">
            <label for="selectFacilities">Select Facilities</label>
            <select name="facility_ids[]" class="select2" multiple="multiple" data-placeholder="Select facilities" id="selectFacilities">
                <?php foreach ($facilities as $facility) : ?>
                    <option value="<?php echo $facility->id ?>"><?php echo $facility->name . ' (' . $facility->mfl_code . ')' ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<script>

$(function() {
        $('.select2').select2()

        $('#formAddFacilities').submit(e => {
            e.preventDefault()
            let formAddFacilities = document.getElementById('formAddFacilities');
            
            let formData = new FormData(formAddFacilities);
            start_load()

            $.ajax({
                url: '../api/add_facilities_to_team',
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
