<?php


?>

<div class="container-fluid">
    <form action="" id="formAddSection">
        <div class="form-group">
            <label for="selectFacilities">Section Name</label>
            <input type="text" name="name" id="inputSectionName" class="form-control" placeholder="Enter Section Name" required>

        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="linkedToCommodity" name="linked_to_commodity">
            <label class="form-check-label" for="linkedToCommodity">Linked to Commodity</label>
        </div>
    </form>
</div>

<script>
    $(function() {

        $('#formAddSection').submit(e => {
            e.preventDefault()
            let formAddSection = document.getElementById('formAddSection');

            let formData = new FormData(formAddSection);
            start_load()

            $.ajax({
                url: '../api/hpt/section',
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