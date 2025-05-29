<?php

use Umb\Mentorship\Models\HptAssessment;

$assessments = HptAssessment::all();
?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <button class="btn btn-block btn-sm btn-default btn-flat border-primary" id="btnAddAssessment"><i class="fa fa-plus"></i> Add HPT Assessment</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Facility</th>
                        <th>Sub County</th>
                        <th>Ward</th>
                        <th>Facility Ownership</th>
                        <th>KEPH Level</th>
                        <th>Review Period</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($assessments as $assessment) :
                    ?>
                        <tr>
                            <th class="text-center"><?php echo $i++ ?></th>
                            <td><b><?php echo ucwords($assessment->facility->name) ?></b></td>
                            <td><b><?php echo ucwords($assessment->sub_county)  ?></b></td>
                            <td><b><?php echo ucwords($assessment->ward)  ?></b></td>
                            <td><b><?php echo ucwords($assessment->facility_ownership == "Other" ? $assessment->facility_ownership_other : $assessment->facility_ownership)  ?></b></td>
                            <td><b><?php echo ucwords($assessment->keph_level)  ?></b></td>
                            <td><b><?php echo ucwords(string: $assessment->review_period_start . " -> " . $assessment->review_period_end) ?></b></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="./index?page=hpt-assessment&id=<?php echo $assessment->id ?>">View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_assessment" href="javascript:void(0)" data-id="<?php echo $assessment->id ?>">Delete</a>
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
    $(document).ready(function () {
        $('#list').dataTable();

        $('#btnAddAssessment').click(function () {
            uni_modal("Add HPT Assessment", "hpt/dialog_add_assessment.php", "large");
        });
        $('.delete_assessment').click(function () {
            _conf("Are you sure to delete this assessment?", "delete_assessment", [$(this).attr('data-id')]);
        });
    });
</script>
