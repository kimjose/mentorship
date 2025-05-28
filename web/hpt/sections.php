<?php

/** @var Umb\Mentorship\Models\User $currUser */

use Umb\Mentorship\Models\HptSection;

$sections = HptSection::all();

if ($currUser->getCategory()->access_level !== 'System') :
?>
    <script>
        window.location.replace("index")
    </script>
<?php endif; ?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <button class="btn btn-block btn-sm btn-default btn-flat border-primary" id="btnAddSection"><i class="fa fa-plus"></i> Add HPT Section</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Linked to Commodity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($sections as $section) :
                    ?>
                        <tr>
                            <th class="text-center"><?php echo $i++ ?></th>
                            <td><b><?php echo ucwords($section->name) ?></b></td>
                            <td><b><?php echo ucwords($section->linked_to_commodity ? "Yes" : "No")  ?></b></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="./index?page=hpt-section&id=<?php echo $section->id ?>">View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_team" href="javascript:void(0)" data-id="<?php echo $section->id ?>">Delete</a>
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
        $('#btnAddSection').click(() => {
            uni_modal("Add HPT Section", `hpt/dialog_add_section`, "large")
        })
    })
</script>