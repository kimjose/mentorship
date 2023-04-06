<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\ChartAbstraction;

$visitId = $_GET['visit_id'];
$abstractionId = '';
if (isset($_GET['abstraction_id'])) {
    $abstractionId = $_GET['abstraction_id'];
    $abstraction = ChartAbstraction::findOrFail($abstractionId);
}
?>
<div class="container-fluid">
    <form action="" id="formAbstraction">
        <input type="hidden" name="visit_id" value="<?php echo $visitId ?>">

        <div class="form-group">
            <label for="inputCccNumber" class="control-label">CCC Number</label>
            <input type="number" name="ccc_number" id="inputCccNumber" cols="30" rows="4" class="form-control" value="<?php echo $abstractionId == '' ? '' : $abstraction->ccc_number; ?>" required>
        </div>
        <div class="form-group">
            <label for="inputAge" class="control-label">Age</label>
            <input type="number" name="age" id="inputAge" cols="30" rows="4" class="form-control" value="<?php echo $abstractionId == '' ? '' : $abstraction->age; ?>" required>
        </div>
        <h5>Gaps</h5>
        <hr>
        <div id="divGaps">
            <div class="row divGap_0">
                <div class="form-group col-11">
                    <input type="text" name="gaps[]" class="form-control">
                </div>
                <div class="col-auto">
                    <button class="btn btn-border-0">
                        <span class="icon "><i class="fa fa-times"></i> </span>
                    </button>
                </div>
            </div>
        </div>

        <button class="btn btn-outline-primary" type="button" onclick="newGap()">
            <span class="icon "><i class="fa fa-plus"></i> </span>
            <span class="text"> Add Gap </span>
        </button>
    </form>
    <div class="row d-none" id="divGapSample">
        <div class="form-group col-11">
            <input type="text" name="gaps[]" class="form-control">
        </div>
        <div class="col-auto">
            <button class="btn btn-border-0" type="button" >
                <span class="icon "><i class="fa fa-times"></i> </span>
            </button>
        </div>
    </div>
</div>

<style>
    .btn-border-0 {
        border: none;
        outline: none;
    }
</style>
<script>
    const divGaps = document.getElementById('divGaps');
    const divGapSample = document.getElementById('divGapSample');

    function newGap() {
        let i = divGaps.children.length;
        let divId = `divGap_${i}`
        let divGap = divGapSample.cloneNode(true)
        divGap.classList.remove('d-none')
        divGap.setAttribute('id', divId);
        let remButton = divGap.querySelector('button')
        remButton.addEventListener('click', e => {
            removeGap(divId)
        })
        divGaps.appendChild(divGap)
    }

    function removeGap(gapId){
        let divGap = divGaps.querySelector(`#${gapId}`)
        divGaps.removeChild(divGap)
    }

    $(function() {
        $('#formAbstraction').submit(e => {
            e.preventDefault()
            // let formData = new FormData($(this)[0])
            // let question = {};
            // for (let [key, value] of formData.entries()) {
            //     question[key] = value
            // }
            start_load()

            $.ajax({
                url:  '../api/chart_abstraction',
                data: new FormData($('#formAbstraction')),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    if (resp.code == 200) {
                        alert_toast(resp.message, "success");
                        setTimeout(function() {
                            location.reload()
                        }, 1500)
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