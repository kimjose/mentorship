<?php

use Umb\Mentorship\Models\Analytic;
use Umb\Mentorship\Models\Facility;

require_once __DIR__ . '/../../vendor/autoload.php';
$id = $_GET['id'];
$analytic = Analytic::findOrFail($id);

$facilities = Facility::all();
$multiSites = $analytic->analytic_type == "Across Sites" ? true : false;
?>

<div class="container-fluid">
    <form action="" id="formRunAnalytic">
        <input name="analytic_id" hidden value="<?php echo $analytic->id ?>" onsubmit="event.preventDefault()">
        <div class="form-group">
            <label for="selectFacilities">Select Facility<sub>(s)</sub></label>
            <select required name="facility_ids[]" class="select2" <?php if ($multiSites) : ?> multiple="multiple" <?php endif; ?> data-placeholder="Select facilities" id="selectFacilities">
                <?php foreach ($facilities as $facility) : ?>
                    <option value="<?php echo $facility->id ?>"><?php echo $facility->name . ' (' . $facility->mfl_code . ')' ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($multiSites) : ?>
            <div class="form-group">
                <label for="inputEndDate">Date</label>
                <input required type="date" name="end_date" id="inputEndDate" class="form-control">
            </div>
        <?php else : ?>
            <div class="row">
                <div class="form-group col-md-6 col-sm-10">
                    <label for="inputStartDate">Start Date</label>
                    <input required type="date" name="start_date" id="inputStartDate" class="form-control">
                </div>
                <div class="form-group col-md-6 col-sm-10">
                    <label for="inputEndDate">End Date</label>
                    <input required type="date" name="end_date" id="inputEndDate" class="form-control">
                </div>
            </div>
        <?php endif; ?>

    </form>
</div>
<script>

$(function() {
        $('.select2').select2()

        $('#formRunAnalytic').submit(e => {
            e.preventDefault()
            let formRunAnalytic = document.getElementById('formRunAnalytic');
            
            let formData = new FormData(formRunAnalytic);
            start_load()

            $.ajax({
                url: '../api/analytics/run',
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