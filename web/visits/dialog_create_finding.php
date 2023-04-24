<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\VisitFinding;

$visitId = $_GET['visit_id'];
$findingId = '';
if (isset($_GET['finding_id'])) {
    $findingId = $_GET['finding_id'];
    $finding = VisitFinding::findOrFail($findingId);
}
$users = User::all();
?>
<div class="container-fluid">
    <form action="" id="formFinding">
        <input type="hidden" name="visit_id" value="<?php echo $visitId ?>">

        <div class="form-group">
            <label for="inputDescription" class="control-label">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="4" class="form-control" required><?php echo $findingId == '' ? '' : $finding->description; ?></textarea>
        </div>
    </form>
</div>

<script>
    $(function() {
        $('.select2').select2()

        let findingId = '<?php echo $findingId ?>'

        $('#formFinding').submit(e => {
            e.preventDefault()
            let formFinding = document.getElementById('formFinding');
            let inputDescription = document.getElementById('inputDescription');
            let description = $('#inputDescription').val()
            let error = ''
            if (description.trim() === '') {
                inputDescription.focus();
                return;
            }
            let formData = new FormData(formFinding);
            let finding = {}
            for (let [key, value] of formData.entries()) {
                finding[key] = value
            }
            start_load()

            fetch(findingId === '' ? '../api/visit_finding' : `../api/visit_finding/${findingId}`, {
                    method: 'POST',
                    body: JSON.stringify(finding),
                    headers: {
                        "content-type": "application/x-www-form-urlencoded"
                    }
                })
                .then(response => {
                    return response.json()
                })
                .then(response => {
                    if (response.code === 200) {
                        alert_toast(response.message, "success");
                        end_load()
                        setTimeout(function() {
                            $('#uni_modal').modal('hide');
                        }, 800)
                    } else throw new Error(response.message)
                })
                .catch(err => {
                    end_load()
                    alert_toast(err.message)
                })

        })
    })
</script>