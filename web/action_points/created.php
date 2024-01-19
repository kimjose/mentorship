<?php

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\ApComment;
use Illuminate\Database\Capsule\Manager as DB;

$query = "select ap.*, concat(u.first_name, ' ', u.last_name), q.question, s.abbr as 'section_abbr', s.title as 'section_title', c.abbr 'checklist_abbr', c.title 'checklist_title', fv.visit_date, f.name 'facility_name' from action_points ap left join users u on u.id = ap.created_by LEFT join questions q on q.id = ap.question_id 
left join sections s on s.id = q.section_id LEFT join checklists c on c.id = s.checklist_id left join facility_visits fv on ap.visit_id = fv.id LEFT join facilities f on f.id = ap.facility_id where ap.created_by = {$currUser->id};";
$actionPoints = DB::select($query);


$doneBadge = "<span class='badge badge-success rounded-pill'>Done</span>";
$pendingBadge = "<span class='badge badge-warning rounded-pill'>Pending</span>";
$overdueBadge = "<span class='badge badge-danger rounded-pill'>Overdue</span>";
function badge($actionPoint){
    global $doneBadge, $overdueBadge, $pendingBadge;
    if($actionPoint->status === 'Done') return $doneBadge;
    $dueDate = date_create(date($actionPoint->due_date . ' 23:59:59'));
    $today =date_create(date('Y-m-d G:i:s'));
    // $diff = date_diff($today, $dueDate)->format("%r%a");
    // return $diff;
    if($dueDate < $today) return $overdueBadge;
    else return $pendingBadge;
}
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item active"> Action Points</li>
    </ol>

</div>
<!-- Page Heading end -->

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button class="btn btn-primary ml-auto float-right btn-icon-split" id="btnAddActionPoint">
            <span class="icon text-white-50"><i class="fa fa-plus"></i> </span>
            <span class="text"> New Action Point</span>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tableVisits">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Facility/Visit Details</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date Due</th>
                        <th>Checklist</th>
                        <th>Section</th>
                        <th>Question</th>
                        <th>Assigned To</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Facility/Visit Details</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date Due</th>
                        <th>Checklist</th>
                        <th>Section</th>
                        <th>Question</th>
                        <th>Assigned To</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($actionPoints

                        as $ap) :
                        $a = explode(',', $ap->assign_to);
                        /** @var User[] $assigned */
                        $assigned = User::whereIn('id', $a)->get();
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
                            <td><abbr title="<?php echo $ap->checklist_title ?>"> <?php echo $ap->checklist_abbr ?></abbr>
                            </td>
                            <td><abbr title="<?php echo $ap->section_title ?>"> <?php echo $ap->section_abbr ?></abbr></td>
                            <td><?php echo $ap->question ?></td>
                            <td>
                                <ul class="list-inline">
                                    <?php foreach ($assigned as $u) :
                                    ?>
                                        <li class="list-inline-item assigned-tag"> <?php echo $u->getNames(); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
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
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-info btn-flat" data-tooltip="tooltip" title="Add Comment" onclick='addComment(<?php echo $ap->id ?>)' data-toggle="modal" data-target="#modalComment">
                                        <i class="far fa-comment"></i>
                                    </button>
                                    <?php if (hasPermission(PERM_CREATE_VISIT, $currUser)) : ?>
                                        <?php if ($currUser->id == $ap->created_by) : ?>
                                            <button type="button" class="btn btn-outline-success btn-flat" title="Mark as Done" data-id="<?php echo $ap->id ?>" onclick='markAsDone(<?php echo $ap->id; ?>)'>
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                    <?php $i++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add comment dialog -->
<div class="modal fade" id="modalComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Add Comment </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" onsubmit="event.preventDefault();" id="formComment">

                <div class="modal-body">

                    <div class="form-group">
                        <label for="inputComment">Comment</label>
                        <textarea name="comment" cols="30" rows="10" class="form-control" id="inputComment"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="savebtn" id="btnSaveComment" class="btn btn-primary" onclick="saveComment()">Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add comment dialog  -->

<script>
    const inputComment = document.querySelector('#inputComment')
    const btnSaveComment = document.querySelector('#btnSaveComment')
    let apId = ''
    const access_level = '<?php echo $currUser->getCategory()->access_level; ?>'
    const facility_id = '<?php echo $currUser->facility_id ?>'

    function initialize() {
        $("#modalComment").on("hide.bs.modal", () => {
            apId = ''
            document.querySelector("#formComment").reset()
        });
        $('#btnAddActionPoint').click(() => {
            uni_modal("New Action Point", `action_points/dialog_create_action_point?access_level=${access_level}&facility_id=${facility_id}`, "large")
        })
    }

    function addComment(id) {
        apId = id
    }

    function saveComment() {
        let comment = inputComment.value.trim();
        if (comment === '') {
            toastr.error('Enter a valid comment.')
            return
        }
        let data = {
            comment: comment,
            ap_id: apId
        }
        start_load()
        fetch('../api/ap_comment', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    "content-type": "application/x-www-form-urlencoded"
                }
            })
            .then(response => {
                return response.json();
            })
            .then(response => {
                end_load()
                if (response.code === 200) {
                    toastr.success("Success")
                    setTimeout(() => {
                        location.reload()
                    }, 1101)
                } else throw new Error(response.message)
            })
            .catch(err => {
                end_load()
                toastr.error(err.message)
            })
    }

    function markAsDone(id) {
        customConfirm("Mark as Done", "Confirm mark this action as done!!", () => {
            fetch('../api/mark_as_done', {
                    method: 'POST',
                    body: JSON.stringify({
                        id: id
                    }),
                    headers: {
                        "content-type": "application/x-www-form-urlencoded"
                    }
                })
                .then(response => {
                    return response.json()
                })
                .then(response => {
                    console.log('response is' + response)
                    if (response.code === 200) {
                        toastr.success(response.message)
                        setTimeout(() => {
                            location.reload()
                        }, 998)
                    } else throw new Error(response.message)
                })
                .catch(err => {
                    toastr.error(err.message)
                })
        }, () => {})
    }

    initialize()
</script>