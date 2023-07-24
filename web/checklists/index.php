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
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index?page=checklists-edit"><i class="fa fa-plus"></i> Add New Checklist</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-bordered" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                    <col width="15%">
                </colgroup>
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
<script>
    $(document).ready(function() {
        $('#list').dataTable()
        $('.delete_survey').click(function() {
            _conf("Are you sure to delete this survey?", "delete_survey", [$(this).attr('data-id')])
        })
    })

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