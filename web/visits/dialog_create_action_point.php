<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\User;

$questionId = $_GET['question_id'];
$visitId = $_GET['visit_id'];
$findingId = $_GET['finding_id'];
$abstractionId = $_GET['abstraction_id'] ?? '';
$users = User::all();
?>
<div class="container-fluid">
    <form action="" id="formActionPoint">
        <input type="hidden" name="visit_id" value="<?php echo $visitId ?>">
        <input type="hidden" name="question_id" value="<?php echo $questionId ?>">
        <input type="hidden" name="finding_id" value="<?php echo $findingId ?? '' ?>">
        <input type="hidden" name="abstraction_id" value="<?php echo $abstractionId ?>">
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
            if(title.trim() === '') error = 'Title is required.'
            if (dueDate.trim() === '') error += "\n The due date is required."
            if (error !== ''){
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