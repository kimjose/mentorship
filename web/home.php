<?php

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Facility;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\FacilityVisit;
use Illuminate\Database\Capsule\Manager as DB;
use Umb\Mentorship\Models\Program;
use Umb\Mentorship\Models\Team;

$programs = [];
if ($currUser->getCategory()->access_level == 'Facility') {
  $facility = Facility::findOrFail($currUser->facility_id);
  $programs = Program::where('id', $facility->program_id)->get();
} elseif ($currUser->getCategory()->access_level == 'Program') {
  $programs = Program::where('id', explode(',', $currUser->program_ids))->get();
} else {
  $programs = Program::all();
}
$program_id = $_GET['program_id'] ?? '';
$selected = false;
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between">
  <ol class="breadcrumb mb-4 transparent">

    <li class="breadcrumb-item active"> Home </li>
  </ol>
  <form action="">
    <div class="form-group">
      <select name="program" id="selectProgram" class="form-control" onchange="selectProgramChanged()">
        <option value="" hidden>Select program</option>
        <?php for ($i = 0; $i < sizeof($programs); $i++) :
          $program = $programs[$i];
          if ($program_id == '' && $i == 0) $program_id = $program->id;
          if ($program_id == $program->id) $selected = true;
          if ($i == (sizeof($programs) - 1) && !$selected) : $program_id = $program->id;
        ?>
            <script>
              location.replace("index?program_id=<?php echo $program_id; ?>")
            </script>
          <?php endif; ?>
          <option value="<?php echo $program->id ?>" <?php echo $program_id == $program->id ? 'selected' : '' ?>><?php echo $program->name ?></option>
        <?php
        endfor;
        ?>
      </select>
    </div>
  </form>
</div>

<?php

$thisMonth = date('y-m-01');

$startDate = date_create(date('y-m-d'));
$endDate = date('Y-m-d');
date_sub($startDate, date_interval_create_from_date_string("60 days"));
$startDate = date_format($startDate, 'Y-m-d');

$users = DB::select("select * from users where active = ? and $program_id in (program_ids)", [1]);
$facilities = DB::select("select f.*, (select COUNT(fv.facility_id) from facility_visits fv where fv.facility_id = f.id GROUP BY fv.facility_id) as visits from facilities f where f.program_id = $program_id order by visits desc;");
$checklists = Checklist::where('status', 'published')->get();
/** @var FacilityVisit[] $periodVisits */
$periodVisits = DB::select("select fv.*, f.team_id from facility_visits fv left join facilities f on f.id = fv.facility_id where f.program_id = ? and fv.visit_date between ? and ? order by fv.visit_date asc", [$program_id, $startDate, $endDate]);
$responses = DB::select("select r.visit_id, r.question_id, q.category, q.frequency_id from responses r left join questions q on q.id = r.question_id left join facility_visits v on v.id = r.visit_id left join facilities f on f.id = v.facility_id where f.program_id = $program_id");
$teams = Team::where('program_id', $program_id)->get();
?>

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
            <ul class=" p-0" style="list-style:none; overflow-y:auto; overflow-x:hidden; min-height: 260px; height: 260px; max-height: 260px;">
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
            <a href="index?page=facilities" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-header bg-warning">
            <h6 class="card-title"> Facilities with least visits</h6>
          </div>
          <div class="card-body">
            <ul class=" p-0" style="list-style:none; overflow-y:auto; overflow-x:hidden; min-height: 260px; height: 260px; max-height: 260px;">
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
            <a href="index?page=facilities" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Response Category Analysis</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
        <canvas id="canvasResponse" height="300" style="height: 300px;"></canvas>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer p-0">
    </div>
    <!-- /.footer -->
  </div>

  <!-- Custom tabs (Charts with tabs)-->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-chart-pie mr-1"></i>
        Visits
      </h3>
      <div class="card-tools">
        <ul class="nav nav-pills ml-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
          </li>
        </ul>
      </div>
    </div><!-- /.card-header -->
    <div class="card-body">
      <div class="tab-content p-0">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
          <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
        </div>
        <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
          <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
        </div>
      </div>
    </div><!-- /.card-body -->
  </div>

</div>
<?php
/*
  // Action Points Statistics
  $dashboardStats = [
    'totalActionPoints' => count($actionPoints),
    'pendingActionPoints' => array_reduce($actionPoints, function ($carry, $item) {
        return $carry + ($item->status === 'Pending' ? 1 : 0);
    }, 0),
    'overdueActionPoints' => array_reduce($actionPoints, function ($carry, $item) {
        $dueDate = date_create($item->due_date . ' 23:59:59');
        $today = date_create(date('Y-m-d G:i:s'));
        return $carry + ($dueDate < $today ? 1 : 0);
    }, 0),
    // Add more dashboard statistics here
    'averageCompletionTime' => calculateAverageCompletionTime($actionPoints),
    'actionPointsByCategory' => countActionPointsByCategory($actionPoints),
    // Add more dashboard statistics as needed
];
*/
?>


<!-- Add more dashboard sections to display additional statistics -->
<!-- <div class="dashboard-section">
    <h2>Dashboard</h2>
    <div>Total Action Points: <?php echo $dashboardStats['totalActionPoints']; ?></div>
    <div>Pending Action Points: <?php echo $dashboardStats['pendingActionPoints']; ?></div>
    <div>Overdue Action Points: <?php echo $dashboardStats['overdueActionPoints']; ?></div>
    <div>Average Completion Time: <?php echo $dashboardStats['averageCompletionTime']; ?></div>
    <div>Action Points by Category: <?php echo json_encode($dashboardStats['actionPointsByCategory']); ?></div>
    
</div> -->

<script>
  const startDateString = "<?php echo $startDate; ?>"
  const endDateString = "<?php echo $endDate; ?>"
  const visits = JSON.parse('<?php echo json_encode($periodVisits) ?>');
  const responses = JSON.parse('<?php echo json_encode($responses) ?>')
  const teams = JSON.parse('<?php echo json_encode($teams) ?>')

  function selectProgramChanged() {
    let selected = $("#selectProgram").val();
    location.replace(`index?program_id=${selected}`);
  }

  function drawResponseDonut() {
    let canvasResponse = $('#canvasResponse').get(0).getContext('2d')
    let labels = ['Facility', 'SDP', 'Individual']
    let data = [0, 0, 0]
    responses.forEach(response => {
      if (response.category === 'facility') data[0]++
      if (response.category === 'sdp') data[1]++
      if (response.category === 'individual') data[2]++
    })
    let pieData = {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: ['#f56954', '#00a65a', '#f39c12']
      }]
    }
    var pieOptions = {
      legend: {
        display: false
      },
      maintainAspectRatio: false,
      responsive: true
    }
    let pieChart = new Chart(canvasResponse, { // lgtm[js/unused-local-variable]
      type: 'doughnut',
      data: pieData,
      options: pieOptions
    })
  }

  function drawVisitsGraph() {

    console.log(visits);
    let labels = [];
    let values = [];
    let startDate = new Date(startDateString)
    let endDate = new Date(endDateString)
    let diff = (endDate - startDate) / (24 * 3600 * 1000)
    let datasets = []
    let teamsDatasets = []

    for (let i = 0; i <= diff; i++) {
      let mDate = new Date(startDate.getTime() + (i * (24 * 3600 * 1000)))
      let label = DateFormatter.formatDate(mDate, 'MM/DD')
      labels.push(label)
      let dayVisits = visits.filter((visit) => {
        return visit.visit_date === DateFormatter.formatDate(mDate, 'yyyy-MM-DD')
      })
      values.push(dayVisits.length);
      for (let j = 0; j < teams.length; j++) {
        let team = teams[j]
        if (i == 0) {
          let c = "rgb(" + Math.floor(Math.random() * 255) + "," + Math.floor(Math.random() * 255) + "," + Math.floor(Math.random() * 255) + ")"
          let teamDataset = {
            name: team.name,
            label: team.name,
            type: 'line',
            data: [],
            backgroundColor: 'transparent',
            borderColor: c,
            pointBorderColor: c,
            pointBackgroundColor: c,
            fill: false
          }
          teamsDatasets.push(teamDataset)
        }
        let ds = teamsDatasets[j];
        let teamDayVisits = visits.filter(visit => {
          return visit.visit_date === DateFormatter.formatDate(mDate, 'yyyy-MM-DD') && visit.team_id === team.id
        })
        ds.data.push(teamDayVisits.length)
      }
    }

    let ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }
    let mode = 'index'
    let intersect = true
    let $visitorsChart = $('#graphVisitsOverTime')
    // eslint-disable-next-line no-unused-vars
    let visitorsChart = new Chart($visitorsChart, {
      data: {
        labels: labels,
        datasets: [{
          label: 'All visits',
          type: 'line',
          data: values,
          backgroundColor: 'transparent',
          borderColor: '#007bff',
          pointBorderColor: '#007bff',
          pointBackgroundColor: '#007bff',
          fill: false
          // pointHoverBackgroundColor: '#007bff',
          // pointHoverBorderColor    : '#007bff'
        }, ...teamsDatasets]
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
          display: true
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
  drawResponseDonut()
</script>