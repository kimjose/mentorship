<?php

use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\FacilityVisit;
use Umb\Mentorship\Models\Section;
use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\VisitSection;

if (!isset($_GET['id'])) :
?>
    <script>
        window.location.replace('index?page=visits');
    </script>
<?php endif; ?>

<?php
$id = $_GET['id'];
$visit = FacilityVisit::find($id);
if ($visit == null) return;
$visitSections = VisitSection::where('visit_id', $id)->get();
$checklists = Checklist::all();


$openedBadge = "<span class='badge badge-primary rounded-pill'>Opened</span>";
$notOpenedBadge = "<span class='badge badge-warning rounded-pill'>Not Opened</span>";
?>
<div class="card">
    <div class="card-header card-secondary card-outline card-outline-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link  active" id="tabSections" data-toggle="pill" href="#tabContentSections" role="tab" aria-controls="tabContentVisit" aria-selected="true">Checklists</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabActionPoints" data-toggle="pill" href="#tabContentActionPoints" role="tab" aria-controls="#tabContentActionPoints" aria-selected="false">Action Points</a>
            </li>

        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="tabContentVisit">
            <div class="tab-pane fade show active" id="tabContentSections" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">


                <?php foreach ($checklists as $checklist) :
                    $sections = Section::where('checklist_id', $checklist->id)->get();
                ?>
                    <div class="card shadow mb-4">
                        <!-- Card Header - Accordion -->
                        <a href="#collapseCardChecklist<?php echo $checklist->id ?>" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                            <h6 class="m-0 font-weight-bold text-primary text-center"><?php echo $checklist->title ?></h6>
                        </a>
                        <!-- Card Content - Collapse -->
                        <div class="collapse hide" id="collapseCardChecklist<?php echo $checklist->id ?>">
                            <div class="card-body">
                                <div class="col-auto table-responsive">
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Abbreviation</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sections as $section) : ?>
                                                <tr>
                                                    <td></td>
                                                    <td><?php echo $section->title ?></td>
                                                    <td><?php echo $section->abbr ?></td>
                                                    <td>
                                                        <?php
                                                        $openedSection = VisitSection::where('visit_id', $id)->where('section_id', $section->id)->first();
                                                        if ($openedSection == null) {
                                                            echo $notOpenedBadge;
                                                        } else {
                                                            echo $openedBadge . "<br>";
                                                            $opener = User::findOrFail($openedSection->user_id);
                                                            echo $opener->first_name . " " . $opener->last_name;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="Edit Section" onclick='openSection("<?php echo $section->id; ?>")'>
                                                                <i class="fas fa-edit"></i>
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
                    </div>
                <?php endforeach; ?>


            </div>
            <div class="tab-pane fade show" id="tabContentActionPoints" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

            </div>
        </div>
    </div>
</div>


<script>
    const visitId = '<?php echo $id ?>'

    function openSection(sectionId) {
        let data = {
            section_id: sectionId,
            visit_id: visitId
        }
        fetch('../api/open_visit_section', {
                method: 'POST',
                body: JSON.stringify(data)
            })
            .then(response => {
                return response.json()
            })
            .then(response => {
                if (response.code === 200) {
                    window.location.replace(`index?page=visits-_section&visit=${visitId}&section=${sectionId}`)
                } else {
                    throw new Error(response.message)
                }
            })
            .catch(err => {
                toastr.error(err.message)
            })
    }
</script>
