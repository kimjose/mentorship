<?php

use Umb\Mentorship\Models\Analytic;
use Umb\Mentorship\Models\AnalyticQuestion;
use Umb\Mentorship\Models\AnalyticRun;
use Umb\Mentorship\Models\Facility;

if (!isset($_GET['id'])) :
?>
    <script>
        window.location.replace('index?page=analytics');
    </script>
<?php
endif;
$id = $_GET['id'];
$analytic = Analytic::find($id);
if ($analytic == null) return;
$analyticQuestions = AnalyticQuestion::where('analytic_id', $analytic->id)->get();

/** @var AnalyticRun[] */
$runs = AnalyticRun::where('analytic_id', $id)->get();
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
    <ol class="breadcrumb mb-4 transparent">
        <li class="breadcrumb-item">
            <a href="index">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="index?page=analytics">Analytics</a>
        </li>
        <li class="breadcrumb-item active"> View </li>
    </ol>

</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Name
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $analytic->name; ?></div>
                    </div>
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Description
                        </div>
                        <div class=" mb-0 font-weight-bold text-gray-800"><?php echo $analytic->description; ?></div>
                    </div>
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Type
                        </div>
                        <div class=" mb-0 font-weight-bold text-gray-800"><?php echo $analytic->analytic_type; ?></div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="col">
                        <div class="h4 font-weight-bold text-primary text-uppercase mb-1">
                            Questions
                        </div>
                        <ul>
                            <?php foreach ($analyticQuestions as $analyticQuestion) : ?>
                                <li>
                                    <?php echo $analyticQuestion->question()->question ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
            <button class="btn btn-secondary ml-auto float-right btn-icon-split" id="btnRunAnalytic" onclick="runAnalytic(<?php echo $analytic->id ?>)">
                <span class="icon text-white-50"><i class="fa fa-play"></i> </span>
                <span class="text"> Run Analytic</span>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Facility(s)</th>
                            <th>Created By</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($runs as $run) :
                            $facilities = Facility::whereIn('id', explode(",", $run->facility_ids))->get();
                            $facilitiesNames = "";
                            foreach ($facilities as $facility) {
                                $facilitiesNames .= $facilitiesNames == "" ? "" : ", ";
                                $facilitiesNames .= $facility->name;
                            }
                        ?>
                            <tr>
                                <td><?php echo $facilitiesNames ?></td>
                                <td><?php echo $run->creator()->getNames() ?></td>
                                <td><?php echo $run->start_date ?></td>
                                <td><?php echo $run->end_date ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-flat" data-tooltip="tooltip" title="View Analytic" onclick='viewRun(<?php echo $run->id; ?>)'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-flat" data-tooltip="tooltip" title="Run Analytic" data-id="<?php echo $analytic->id ?>" onclick='runAnalytic(<?php echo $analytic->id; ?>)'>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Facility(s)</th>
                            <th>Created By</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function runAnalytic(id) {
        uni_modal("Run Analytic", `analytics/dialog_run_analytic?id=${id}`, "large")
    }
    function viewRun(id){
        view_modal("View Analysis Output", `analytics/dialog_view_run_results?id=${id}`, "large")
    }
</script>