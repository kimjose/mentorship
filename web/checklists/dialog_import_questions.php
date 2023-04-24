<?php

use Umb\Mentorship\Models\Section;

require_once __DIR__ . '/../../vendor/autoload.php';

$sectionId = $_GET["section_id"];
$section = Section::find($sectionId);
?>
<div class="container-fluid">
    <div class="card-header">
        <h6><?php echo $section->title; ?></h6>
    </div>
    <form action="" id="formImportQuestions">
        <input type="hidden" name="section_id" value="<?php echo $section->id; ?>">
        <div class="form-group">
            <div class="custom-file">
                <input type="file" accept=".xlsx,.csv" class="custom-file-input" id="customFile" name="upload_file" required onchange="updateLabel(this,$(this), 'labelFile')">
                <label id="labelFile" class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
    </form>
</div>

<script>
    const formImportQuestions = document.querySelector("#formImportQuestions")

    function updateLabel(input, _this, labelId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(`#${labelId}`).html(input.files[0].name);
            }

            reader.readAsDataURL(input.files[0]);
            console.dir(input.files[0])
        }
    }

    $(function() {
        $('#formImportQuestions').submit(e => {
            e.preventDefault();
            let formData = new FormData(formImportQuestions)
            $.ajax({
                url: '../api/import_questions',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    if (resp.code == 200) {
                        alert_toast('Data successfully imported.', "success");
                        setTimeout(function() {
                           location.reload()
                        }, 1500)
                    } else {
                        toastr.error(resp.message)
                    }
                },
                error: function(request, status, error) {
                    toastr.error(request.responseText);
                }
            })
        });
    })
</script>