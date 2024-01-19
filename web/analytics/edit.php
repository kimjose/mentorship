<?php

use Umb\Mentorship\Models\Checklist;
use Illuminate\Database\Capsule\Manager as DB;

/** @var Checklist[] */
$checklists = Checklist::where('status', 'published')->get();
$questions = DB::select("select q.id, q.question, q.category, q.frequency_id, q.type, q.section_id, s.checklist_id  from questions q LEFT join sections s on s.id = q.section_id left join checklists c on c.id = s.checklist_id where c.status like 'published';");

?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="index?page=analytics">Analytics</a>
        </li>
        <li class="breadcrumb-item active"> Edit </li>
    </ol>

</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="" id="formAnalytic" onsubmit="event.preventDefault()">
                <div class="form-group">
                    <label for="inputName">Name</label>
                    <input type="text" name="name" id="inputName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="inputDescription">Description</label>
                    <textarea name="description" id="inputDescription" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="selectAnalyticType">Analytic Type</label>
                    <select name="analytic_type" id="selectAnalyticType" class="form-control">
                        <option value="" hidden>Select Type</option>
                        <option value="Longitudinal">Longitudinal (One site over a period of time)</option>
                        <option value="Across Sites"> Across Sites</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="selectChecklist">Checklist</label>
                    <select name="checklist_id" id="selectChecklist" class="form-control" onchange="selectChecklistChanged()">
                        <option value="" hidden>Select Type</option>
                        <?php foreach ($checklists as $checklist) : ?>
                            <option value="<?php echo $checklist->id ?>"><?php echo $checklist->title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Select Response Type</label>
                    <select name="qn_type" id="selectQnType" class="form-control" onchange="selectQnTypeChanged()">
                        <!-- TODO Update for editing -->
                        <option value="" hidden <?php echo !isset($id) ? 'selected' : '' ?>>Please Select here</option>
                        <option value="radio_opt" <?php echo (isset($id) &&  $question->type == 'radio_opt') ? 'selected' : ''  ?>>Single Answer/Radio Button</option>
                        <option value="check_opt" <?php echo (isset($id) &&  $question->type == 'check_opt') ? 'selected' : ''  ?>>Multiple Answer/Check Boxes</option>
                        <option value="number_opt" <?php echo (isset($id) &&  $question->type == 'number_opt') ? 'selected' : ''  ?>>Number</option>
                    </select>
                </div>
                <div id="divQuestions">
                    <h5>Questions to include in analytics <span class="text-info">(Select checklist and Response type first)</span> </h5>
                    <ul id="listQuestions" style="list-style: none;">
                        
                    </ul>
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2" id="btnSave" onclick="saveAnalytic()">Save</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = 'index?page=analytics'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const questions = JSON.parse('<?php echo json_encode($questions) ?>')
    const formAnalytic = document.getElementById('formAnalytic')
    const inputName = document.getElementById('inputName')
    const inputDescription = document.getElementById('inputDescription')
    const selectAnalyticType = document.getElementById('selectAnalyticType')
    const selectChecklist = document.getElementById('selectChecklist')
    const selectQnType = document.getElementById('selectQnType')
    const divQuestions = document.getElementById('divQuestions')
    const listQuestions = document.getElementById('listQuestions')
    const btnSave = document.getElementById('btnSave')

    function selectChecklistChanged() {
        let selected = $(selectChecklist).val()
        let items = listQuestions.querySelectorAll('li')
        items.forEach(item => {
            listQuestions.removeChild(item)
        })
        $(selectQnType).val('')
    }

    function selectQnTypeChanged() {
        let selected = $(selectQnType).val();
        let checkListId = $(selectChecklist).val()
        let items = listQuestions.querySelectorAll('li')
        items.forEach(item => {
            listQuestions.removeChild(item)
        })
        if (selected === '' || checkListId === '') return;
        let filtered = questions.filter(question => {
            return question.checklist_id == checkListId && question.type == selected
        })
        filtered.forEach(question => {
            let listItem = document.createElement('li')
            let checkBoxId = `qn_${question.question}`
            listItem.innerHTML = `
                            <div class="form-group">
                                <input type="checkbox" name="questions[]" id="${checkBoxId}" class="form-check-input" data-id="${question.id}">
                                <label class="form-check-label" for="${checkBoxId}">${question.question}</label>
                            </div>
            `
            listQuestions.appendChild(listItem)
        })
    }

    function saveAnalytic() {
        let name = inputName.value
        let description = inputDescription.value
        let analyticType = $(selectAnalyticType).val();
        let checklist = $(selectChecklist).val();
        let qnType = $(selectQnType).val();

        let listItems = listQuestions.querySelectorAll('li')
        console.dir(listItems)
        let checked = []
        listItems.forEach(item => {
            let checkbox = item.querySelector('input[type=checkbox]')
            if (checkbox.checked) {
                checked.push(checkbox.getAttribute('data-id'))
            }
        })
        if (name == '') {
            toastr.error('Name is required')
            inputName.focus()
            return
        }
        if (analyticType === '') {
            toastr.error('Analytic type is required')
            selectAnalyticType.focus()
            return
        }
        if (checklist === '') {
            toastr.error('Checklist is required')
            selectChecklist.focus()
            return
        }
        if (qnType === '') {
            toastr.error('Response type is required')
            selectQnType.focus()
            return
        }
        if (checked.length === 0) {
            toastr.error('No questions checked')
            return
        }
        let analytic = {
            name: name,
            description: description,
            analytic_type: analyticType,
            checklist_id: checklist,
            qn_type: qnType,
            question_ids: checked.toString()
        }
        btnSave.setAttribute('disabled', '')
        fetch('../api/analytics/create', {
                method: 'POST',
                body: JSON.stringify(analytic),
                headers: {
                    "content-type": "application/x-www-form-urlencoded"
                }
            })
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    alert_toast(response.message,'success')
                    window.location.replace('index?page=analytics')
                } else throw new Error(response.message)
            })
            .catch(err => {
                btnSave.removeAttribute('disabled')
                toastr.error(err.message)
            })
    }
</script>