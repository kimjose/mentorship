<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Facility;

$facilities = [];
$access_level = $_GET['access_level'] ?? 'Program';
$facility_id = $_GET['facility_id'] ?? '';
$users = [];
if ($access_level == 'Facility') {
	$users = User::where('facility_id', $facility_id)->get();
    $facilities = Facility::where('id', $facility_id)->get();
} else {
	$users = User::all();
    $facilities = Facility::all();
}
?>
<div class="container-fluid">
    <form action="" id="formActionPoint">
        <div class="form-group">
            <label for="selectFacility">Facility</label>
            <select name="facility_id" id="selectFacility" class="form-control select2" required >
                <option value="" selected hidden>Select facility</option>
                <?php foreach ($facilities as $facility) : ?>
                    <option value="<?php echo $facility->id ?>"><?php echo $facility->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="inputTitle" class="control-label">Title</label>
            <input type="text" name="title" id="inputTitle" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="inputDescription" class="control-label">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="4" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="inputDueDate">Due Date</label>
            <input type="date" name="due_date" id="inputDueDate" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="selectAssignTo">Assign to</label>
            <select name="assign_to[]" id="selectAssignTo" class="select2" multiple="multiple" data-placeholder="Select persons">
                <?php
                foreach ($users as $user) :
                ?>
                    <option value="<?php echo $user->id ?>"><?php echo $user->first_name . ' ' . $user->last_name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<script>
    $(function() {
        $('.select2').select2()

        $('#formActionPoint').submit(e => {
            e.preventDefault()
            let formActionPoint = document.getElementById('formActionPoint');
            let title = $('#inputTitle').val()
            let dueDate = $('#inputDueDate').val()
            let error = ''
            if (title.trim() === '') error = 'Title is required.'
            if (dueDate.trim() === '') error += "\n The due date is required."
            if (error !== '') {
                toastr.error(error)
                return;
            }
            let formData = new FormData(formActionPoint);
            start_load()

            $.ajax({
                url: '../api/action_point',
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
                            location.reload();
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