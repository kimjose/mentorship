<?php

use Umb\Mentorship\Controllers\QuestionsBuilder;

$builder = new QuestionsBuilder();
$checklists = $builder->getChecklists();
?>
<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_survey"><i class="fa fa-plus"></i> Add New Checklist</a>
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
                        <th>Start</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($checklists as $checklist) :
                    ?>
                        <tr>
                            <th class="text-center"><?php echo $i++ ?></th>
                            <td><b><?php echo ucwords($checklist->title) ?></b></td>
                            <td><b><?php echo ucwords($checklist->abbr) ?></b></td>
                            <td><b class="truncate"><?php echo $checklist->description ?></b></td>
                            <td><b><?php echo date("M d, Y", strtotime($checklist->created_at)) ?></b></td>
                            <td class="text-center">
                                <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item" href="./index.php?page=edit_survey&id=<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_survey" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
		                    </div> -->
                                <div class="btn-group">
                                    <a href="./index?page=checklists-edit&id=<?php echo $checklist->id ?>" class="btn btn-primary btn-flat">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./index?page=checklists-view&id=<?php echo $checklist->id ?>" class="btn btn-info btn-flat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-flat delete_survey" data-id="<?php echo $checklist->id ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
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