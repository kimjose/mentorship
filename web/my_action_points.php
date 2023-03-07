<?php

use Illuminate\Database\Capsule\Manager as DB;

$query = "select ap.*, concat(u.first_name, ' ', u.last_name), q.question, s.abbr as 'section_abbr', s.title as 'section_title', c.abbr 'checklist_abbr', c.title 'checklist_title', fv.visit_date, f.name 'facility_name' from action_points ap left join users u on u.id = ap.created_by LEFT join questions q on q.id = ap.question_id 
left join sections s on s.id = q.section_id LEFT join checklists c on c.id = s.checklist_id left join facility_visits fv on ap.visit_id = fv.id LEFT join facilities f on f.id = fv.facility_id;";
$actionPoints = DB::select($query);
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Action Points </li>
    </ol>

</div>
<!-- Page Heading end -->

<div class="card shadow mb-4">
    <div class="card-header py-3">

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableVisits">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Visit Details</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date Due</th>
                        <th>Checklist</th>
                        <th>Section</th>
                        <th>Question</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Visit Details</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date Due</th>
                        <th>Checklist</th>
                        <th>Section</th>
                        <th>Question</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($actionPoints as $ap) :
                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td>
                                <p><?php echo $ap->facility_name ?></p>
                                <b><?php echo $ap->visit_date ?></b>
                            </td>
                            <td><?php echo $ap->title  ?></td>
                            <td><?php echo $ap->description ?></td>
                            <td><?php echo $ap->due_date ?></td>
                            <td><abbr title="<?php echo $ap->checklist_title ?>"> <?php echo $ap->checklist_abbr ?></abbr></td>
                            <td><abbr title="<?php echo $ap->section_title ?>"> <?php echo $ap->section_abbr ?></abbr></td>
                            <td><?php echo $ap->question  ?></td>
                            <td></td>
                            <td class="text-center">
                                
                            </td>
                        </tr>

                    <?php $i++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>