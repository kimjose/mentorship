<?php

use Umb\Mentorship\Models\Analytic;
use Umb\Mentorship\Models\AnalyticQuestion;

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
$analyticQuestions = AnalyticQuestion::where('analytic_id', $analytic->id)->get()
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
            <table>
                <thead></thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
</div>

<script>

    function runAnalytic(id){
        uni_modal("Run Analytic", `analytics/dialog_run_analytic?id=${id}`, "large")
    }
</script>
