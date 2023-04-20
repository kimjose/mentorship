<?php

use Umb\Mentorship\Models\Analytic;

$analytics = Analytic::all();

?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>

        <li class="breadcrumb-item active"> Analytics </li>
    </ol>

</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="index?page=analytics-edit" class="btn btn-primary ml-auto float-right btn-icon-split" id="btnAddAnalytic">
            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
            <span class="text"> Add Analytic</span>
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table-bordered table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created By</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created By</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($analytics as $analytic) : ?>
                        <tr>
                            <td></td>
                            <td><?php echo $analytic->name; ?></td>
                            <td><?php echo $analytic->description; ?></td>
                            <td><?php echo $analytic->creator()->getNames(); ?></td>
                            <td><?php echo $analytic->analytic_type ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="View Analytic" onclick='viewAnalytic(<?php echo $analytic->id; ?>)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-flat" data-tooltip="tooltip" title="Run Analytic" data-id="<?php echo $analytic->id ?>" onclick='runAnalytic(<?php echo $analytic->id; ?>)'>
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
    function viewAnalytic(analyticId){

    }

    function runAnalytic(analyticId){
        
    }

</script>
