<?php

use Umb\Mentorship\Models\User;
use Umb\Mentorship\Models\Facility;
use Umb\Mentorship\Models\Checklist;
use Umb\Mentorship\Models\FacilityVisit;

$thisMonth = date('y-m-01');

$users = User::all();
$facilities = Facility::all();
$checklists = Checklist::where('status', 'published')->get();
/** @var FacilityVisit[] */
$thisMonthVisits = FacilityVisit::where('visit_date', '>=', $thisMonth)->get();

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
        <h3><?php echo sizeof($thisMonthVisits) ?></h3>

        <p>This Month facility visits</p>
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
  <div class="col-md-8 col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa fa-chart-line mr-1"></i>
          Visits Over Time
        </h3>
      </div>
      <div class="card-body">
        <canvas class="chart" id="graphVisitsOverTime" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-12">
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header bg-primary">
            <h6 class="card-title"> Facilities with most visits</h6>
          </div>
          <div class="card-body p-2">
            <ul style="list-style:disc;">
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-header bg-warning">
            <h6 class="card-title"> Facilities with least visits</h6>
          </div>
          <div class="card-body p-2">
            <ul style="list-style:disc;">
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
              <li>
                <b>Kiamaiko Health Center</b>
                <p>5</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>