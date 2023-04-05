<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\ApComment;
use Umb\Mentorship\Models\ActionPoint;
use Illuminate\Database\Capsule\Manager as DB;

$ids = $_GET['ids'];

$apIds = explode(',', $ids);
$query = "select ap.*, concat(u.first_name, ' ', u.last_name), fv.visit_date, f.name 'facility_name' from action_points ap left join users u on u.id = ap.created_by left join facility_visits fv on ap.visit_id = fv.id LEFT join facilities f on f.id = ap.facility_id where ap.id in({$ids});";
$actionPoints = DB::select($query);

$doneBadge = "<span class='badge badge-success rounded-pill'>Done</span>";
$pendingBadge = "<span class='badge badge-warning rounded-pill'>Pending</span>";
$overdueBadge = "<span class='badge badge-danger rounded-pill'>Overdue</span>";
function badge($actionPoint)
{
    global $doneBadge, $overdueBadge, $pendingBadge;
    if ($actionPoint->status === 'Done') return $doneBadge;
    $dueDate = date_create(date($actionPoint->due_date . ' 23:59:59'));
    $today = date_create(date('Y-m-d G:i:s'));
    // $diff = date_diff($today, $dueDate)->format("%r%a");
    // return $diff;
    if ($dueDate < $today) return $overdueBadge;
    else return $pendingBadge;
}
?>
<div class="container-fluid">
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="tableVisits">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Facility/Visit Details</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date Due</th>
                    <th>Comments</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Facility/Visit Details</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date Due</th>
                    <th>Comments</th>
                    <th>Status</th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                $i = 1;
                foreach ($actionPoints

                    as $ap) :
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td>
                            <p><?php echo $ap->facility_name ?></p>
                            <b><?php echo $ap->visit_date ?></b>
                        </td>
                        <td><?php echo $ap->title ?></td>
                        <td><?php echo $ap->description ?></td>
                        <td><?php echo $ap->due_date ?></td>
                        <td>
                            <?php
                            /** @var ApComment[] $comments */
                            $comments = ApComment::where('ap_id', $ap->id)->get();
                            foreach ($comments as $apComment) :
                            ?>
                                <div class="card mt-1 p-1">
                                    <p><?php echo $apComment->comment ?></p>
                                    <small class="text-info"><?php echo $apComment->creator()->getNames(); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php
                            echo badge($ap);
                            ?>
                        </td>
                    </tr>

                <?php $i++;
                endforeach; ?>
            </tbody>
    </div>
</div>