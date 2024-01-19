<?php

use Umb\Mentorship\Controllers\QuestionsBuilder;

$builder = new QuestionsBuilder();
$checklists = $builder->getChecklists();

$publishedBadge = "<span class='badge badge-primary rounded-pill'>Published</span>";
$draftBadge = "<span class='badge badge-warning rounded-pill'>Draft</span>";
$retiredBadge = "<span class='badge badge-secondary rounded-pill'>Retired</span>";
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Checklists </li>
    </ol>

</div>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <?php if (hasPermission(PERM_CHECKLIST_MANAGEMENT, $currUser)) : ?>
                    <button class="btn btn-block btn-sm btn-default btn-flat border-secondary" data-toggle="modal" data-target="#modalImportChecklist"><i class="fa fa-file-import"></i> Import Checklist</button>
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index?page=checklists-edit"><i class="fa fa-plus"></i> Add New Checklist</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table tabe-hover table-bordered" id="list">
                    
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Title</th>
                            <th>Abbreviation</th>
                            <th>Description</th>
                            <td>Status</td>
                            <th>Start</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($checklists as $checklist) :
                        ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><b><?php echo ucwords($checklist->title) ?></b></td>
                                <td><b><?php echo ucwords($checklist->abbr) ?></b></td>
                                <td><b class="truncate"><?php echo $checklist->description ?></b></td>
                                <td class="text-center"><?php
                                                        switch ($checklist->status) {
                                                            case 'draft':
                                                                echo $draftBadge;
                                                                break;
                                                            case 'published':
                                                                echo $publishedBadge;
                                                                break;
                                                            case 'retired':
                                                                echo $retiredBadge;
                                                                break;
                                                        }
                                                        ?>
                                </td>
                                <td><b><?php echo date("M d, Y", strtotime($checklist->created_at)) ?></b></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="./index?page=checklists-view&id=<?php echo $checklist->id ?>" class="btn btn-info btn-flat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (hasPermission(PERM_CHECKLIST_MANAGEMENT, $currUser)) : ?>
                                            <?php if ($checklist->status == 'draft') : ?>
                                                <a href="./index?page=checklists-edit&id=<?php echo $checklist->id ?>" class="btn btn-primary btn-flat">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-danger btn-flat delete_survey" data-id="<?php echo $checklist->id ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <a href="../api/export_checklist_to_json/<?php echo $checklist->id ?>" class="btn btn-secondary btn-flat" title="Export Checklist">
                                                <i class="fas fa-file-export"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Resource Dialog  -->
<div class="modal fade" id="modalImportChecklist" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Checklist Import Dialog</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" onsubmit="event.preventDefault();" id="formImportChecklist">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">JSON File</label>
                        <div class="custom-file">
                            <input type="file" class="form-control text-primary" id="inputJsonFile" name="resource" accept=".json" onchange="updateLabel(this,$(this), 'labelJsonFile')">
                            <label id="labelJsonFile" class="custom-file-label" for="inputJsonFile">Choose JSON
                                File</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="row">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" name="btn_import" id="btnImportJsonFile" onclick="importJsonFile()" class="btn btn-primary ml-2">Save
                        </button>
                        <div class="col-auto">
                            <div class="loader d-none" id="loaderImportChecklist"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--  Add Resource Dialog End -->

<script>
    const btnImportJsonFile = document.getElementById('btnImportJsonFile')
    const loaderImportChecklist = document.getElementById('loaderImportChecklist')
    $(document).ready(function() {
        $('#list').dataTable()
        $('.delete_survey').click(function() {
            _conf("Are you sure to delete this survey?", "delete_survey", [$(this).attr('data-id')])
        })
    })

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

    function importJsonFile() {
        let formData = new FormData();
        let inputJsonFile = document.getElementById("inputJsonFile")
        let file = inputJsonFile.files[0]
        if (file == null) {
            toastr.error('Select a file first.')
            return
        }
        formData.append('upload_file', file)
        // formData.append('uploaded_by', currUser.id)
        $.ajax({
            url: `../api/import_checklist`,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                if (loaderImportChecklist.classList.contains('d-none')) {
                    loaderImportChecklist.classList.remove('d-none')
                }
                btnImportJsonFile.setAttribute('disabled', '')
                //$("#preview").fadeOut();
                // $("#err").fadeOut();
            },
            success: function(data) {
                let response = data
                loaderImportChecklist.classList.add('d-none')
                btnImportJsonFile.removeAttribute('disabled')
                if (response.code === 200) {
                    toastr.success(response.message)
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toastr.error(response.message)
                    // throw new Error(response.message)
                }
            },
            error: function(e) {
                toastr.error(e.message)
                btnImportJsonFile.removeAttribute('disabled')
            }
        });
    }



    function delete_survey($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_survey',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }
</script>