<?php

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Facility;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\FacilityVisit;
use Illuminate\Database\Capsule\Manager as DB;

$thisMonth = date('y-m-01');

$startDate = date_create(date('y-m-d'));
$endDate = date('Y-m-d');
date_sub($startDate, date_interval_create_from_date_string("60 days"));
$startDate = date_format($startDate, 'Y-m-d');

$users = User::all();
$facilities = DB::select("select f.*, (select COUNT(fv.facility_id) from facility_visits fv where fv.facility_id = f.id GROUP BY fv.facility_id) as visits from facilities f order by visits desc;");
$checklists = Checklist::where('status', 'published')->get();
/** @var FacilityVisit[] */
$periodVisits = FacilityVisit::where('visit_date', '>=', $startDate)->where('visit_date', '<=', $endDate)->orderBy('visit_date', 'asc')->get();

?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
  <ol class="breadcrumb mb-4 transparent">

    <li class="breadcrumb-item active"> Home </li>
  </ol>

</div>

<!-- top row boxes -->
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?php echo sizeof($users); ?></h3>

        <p>Users</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
      <a href="index?page=users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?php echo sizeof($facilities) ?></h3>

        <p>Facilities</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <a href="index?page=facilities" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-secondary">
      <div class="inner">
        <h3><?php echo sizeof($checklists) ?></h3>

        <p>Checklists</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph"></i>
      </div>
      <a href="index?page=checklists" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3><?php echo sizeof($periodVisits) ?></h3>

        <p>Periods facility visits</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add"></i>
      </div>
      <a href="index?page=visits" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

</div>
<!-- /top row boxes -->

<h4>Visits Overview</h4>
<div class="row">
  <div class="col-lg-8 col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa fa-chart-line mr-1"></i>
          Visits Over Time
        </h3>
      </div>
      <div class="card-body">
        <canvas class="chart" id="graphVisitsOverTime" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-12">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header bg-primary">
            <h6 class="card-title"> Facilities with most visits</h6>
          </div>
          <div class="card-body p-2">
            <ul class=" p-0" style="list-style:none;">
              <?php for ($i = 0; $i < 5; $i++) :
                $facility = $facilities[$i]; ?>
                <li class="mt-1">
                  <div class="row" style="">
                    <div class="col-8">
                      <p><?php echo $facility->name ?></p>
                    </div>
                    <div class="col-4">
                      <h4 class="text-center text-info"><?php echo $facility->visits ?? 0 ?></h4>
                    </div>
                  </div>
                </li>
              <?php endfor; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-header bg-warning">
            <h6 class="card-title"> Facilities with least visits</h6>
          </div>
          <div class="card-body">
            <ul class=" p-0" style="list-style:none;">
              <?php for ($i = 1; $i <= 5; $i++) :
                $facility = $facilities[sizeof($facilities) - $i]; ?>
                <li class="mt-1">
                  <div class="row" style="">
                    <div class="col-8">
                      <p><?php echo $facility->name ?></p>
                    </div>
                    <div class="col-4">
                      <h4 class="text-center text-info"><?php echo $facility->visits ?? 0 ?></h4>
                    </div>
                  </div>
                </li>
              <?php endfor; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  const startDateString = "<?php echo $startDate; ?>"
  const endDateString = "<?php echo $endDate; ?>"
  const visits = JSON.parse('<?php echo $periodVisits ?>');

  function drawVisitsGraph() {

    console.log(visits);
    let labels = [];
    let values = [];
    let startDate = new Date(startDateString)
    let endDate = new Date(endDateString)
    let diff = (endDate - startDate) / (24 * 3600 * 1000)
    console.log(`Difference is :  ${diff}`);

    for (let i = 0; i <= diff; i++) {
      let mDate = new Date(startDate.getTime() + (i * (24 * 3600 * 1000)))
      let label = DateFormatter.formatDate(mDate, 'MM/DD')
      labels.push(label)
      let dayVisits = visits.filter((visit) => {
        return visit.visit_date === DateFormatter.formatDate(mDate, 'yyyy-MM-DD')
      })
      values.push(dayVisits.length);
    }

    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }
    var mode = 'index'
    var intersect = true
    var $visitorsChart = $('#graphVisitsOverTime')
    // eslint-disable-next-line no-unused-vars
    var visitorsChart = new Chart($visitorsChart, {
      data: {
        labels: labels,
        datasets: [{
            label: 'visits',
            type: 'line',
            data: values,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            pointBorderColor: '#007bff',
            pointBackgroundColor: '#007bff',
            fill: false
            // pointHoverBackgroundColor: '#007bff',
            // pointHoverBorderColor    : '#007bff'
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          mode: mode,
          intersect: intersect
        },
        hover: {
          mode: mode,
          intersect: intersect
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            scaleLabel: {
              display: true,
              labelString: "No. of Visits",
            },
            gridLines: {
              display: true,
              lineWidth: '4px',
              color: 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks: $.extend({
              beginAtZero: true,
              suggestedMax: 10
            }, ticksStyle)
          }],
          xAxes: [{
            scaleLabel: {
              display: true,
              labelString: "Dates",
            },
            display: true,
            gridLines: {
              display: false
            },
            ticks: ticksStyle
          }]
        }
      }
    })
  }

  drawVisitsGraph()
</script>