<!-- Section Dialog -->
<div class="modal fade" id="modalSection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Section Dialog</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" onsubmit="event.preventDefault();" id="formSection">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputTitle">Title</label>
                        <input type="text" class="form-control" id="inputTitle" placeholder="Enter venue name" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="inputAbbr">Abbreviation</label>
                        <input type="text" class="form-control" id="inputAbbr" placeholder="Abbreviation" name="abbr" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="savebtn" id="btnSaveSection" class="btn btn-primary" onclick="saveSection()">Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Section Dialog end-->

<script>
    let sectionId = ''
    let formSection = document.getElementById('formSection')
    let btnSaveSection = document.getElementById('btnSaveSection')
    const saveSection = () => {
        let formData = new FormData(formSection);
        let section = {};
        for (let [key, value] of formData.entries()) {
            section[key] = value
        }
        if (section.title == '') {
            document.getElementById('inputTitle').focus();
            return
        }
        if (section.abbr == '') {
            document.getElementById('inputAbbr').focus();
            return
        }
        section.checklist_id = checklistId
        btnSaveSection.setAttribute('disabled', '')
        fetch(sectionId == '' ? "../api/section" : `../api/section/${sectionId}`, {
                method: 'POST',
                body: JSON.stringify(section),
                headers: {
                    "content-type": "application/x-www-form-urlencoded"
                }
            })
            .then(response => {
                return response.json()
            })
            .then(response => {
                btnSaveSection.removeAttribute('disabled')
                if (response.code === 200) {
                    window.location.replace("index?page=checklists")
                } else throw new Error(response.message)
            })
            .catch(error => {
                if (btnSaveEventType.hasAttribute('disabled')) btnSaveEventType.removeAttribute('disabled')
                console.log(error.message);
                toastr.error(error.message)
            })
    }
</script>