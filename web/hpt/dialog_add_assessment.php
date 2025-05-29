<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\Facility;

$facilities = Facility::all();
$owners = ["Ministry of Health", "Non-Governmental Organization", "Faith-based Organization", "Private", "Other"];
$kephLevels = ["Level 1", "Level 2", "Level 3", "Level 4", "Level 5", "Level 6"];

?>

<div class="container-fluid">
    <form action="" id="formAddAssessment">
        <div class="form-group">
            <label for="inputDateOfInterview">Date of Interview</label>
            <input type="date" name="date_of_interview" id="inputDateOfInterview" class="form-control"
                placeholder="Select Date of Interview" required>
        </div>
        <div class="form-group">
            <label for="inputSubcounty">Sub County</label>
            <input type="text" name="sub_county" id="inputSubcounty" class="form-control" placeholder="Enter Sub-county"
                required>
        </div>
        <div class="form-group">
            <label for="inputWard">Ward</label>
            <input type="text" name="ward" id="inputWard" class="form-control" placeholder="Enter Ward" required>
        </div>
        <div class="form-group">
            <label for="selectFacility">Facility</label>
            <select name="facility_id" id="selectFacility" class="form-control" required>
                <option value="" selected hidden>Select Facility</option>
                <?php
                foreach ($facilities as $facility): ?>
                    <option value="<?php echo $facility->id; ?>"><?php echo $facility->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="selectOwnership">Facility Ownership</label>
            <select name="facility_ownership" id="selectOwnership" class="form-control" required>
                <option value="" selected hidden>Select Facility Ownership</option>
                <?php foreach ($owners as $owner): ?>
                    <option value="<?php echo $owner; ?>"><?php echo $owner; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="facility_ownership_other" id="inputOwnershipOther" class="form-control mt-2"
                placeholder="If other, specify" hidden>
        </div>
        <div class="form-group">
            <label for="selectKephLevel">Keph Level</label>
            <select name="keph_level" id="selectKephLevel" class="form-control" required>
                <option value="" selected hidden>Select Keph Level</option>
                <?php foreach ($kephLevels as $level): ?>
                    <option value="<?php echo $level; ?>"><?php echo $level; ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <div class="form-group">
            <h6>Review Period</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputReviewPeriodStart">Start Date</label>
                        <input type="date" name="review_period_start" id="inputReviewPeriodStart"
                            class="form-control" placeholder="Select Start Date" required>      
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputReviewPeriodEnd">End Date</label>
                        <input type="date" name="review_period_end" id="inputReviewPeriodEnd"
                            class="form-control" placeholder="Select End Date" required>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#selectOwnership').change(function () {
            if ($(this).val() === 'Other') {
                $('#inputOwnershipOther').removeAttr('hidden');
            } else {
                $('#inputOwnershipOther').attr('hidden', true);
                $('#inputOwnershipOther').val('');
            }
        });

        $('#formAddAssessment').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '../api/hpt/create_assessment',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function (resp) {
                    if (resp.code === 200) {
                        alert_toast('Assessment successfully added.', "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function (err) {
                    toastr.error("An error occurred while adding the section.");
                }
            });
        });
    });
</script>