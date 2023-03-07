  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
   	<a href="javascript:void(0)" class="brand-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <span class="brand-image img-circle elevation-3 d-flex justify-content-center align-items-center bg-primary text-white font-weight-500" style="width: 38px;height:50px"><?php echo strtoupper(substr($_SESSION['login_firstname'], 0,1).substr($_SESSION['login_lastname'], 0,1)) ?></span>
        <span class="brand-text font-weight-light"><?php echo ucwords($currUser->first_name.' '.$currUser->last_name) ?></span>

      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item manage_account" href="javascript:void(0)" data-id="<?php echo $currUser->id ?>">Manage Account</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="ajax?action=logout">Logout</a>
      </div>
    </div>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
            
          </li>    
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index?page=users-edit" class="nav-link nav-users-edit tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index?page=users" class="nav-link nav-users tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-is-tree nav-edit_survey nav-view_survey">
              <i class="nav-icon fa fa-poll-h"></i>
              <p>
                Checklist
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index?page=checklists-edit" class="nav-link nav-checklists-edit tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index?page=checklists" class="nav-link nav-checklists tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="./index?page=survey_report" class="nav-link nav-survey_report">
              <i class="nav-icon fas fa-poll"></i>
              <p>
                Checklist Report
              </p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="./index?page=facilities" class="nav-link nav-facilities">
              <i class="nav-icon fa fa-building"></i>
              <p>
                Facilities
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./index?page=visits" class="nav-link nav-visits nav-visits-open">
              <i class="nav-icon fas fa-route"></i>
              <p>
                Facility Visits
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="./index?page=my_action_points" class="nav-link nav-my_action_points">
              <i class="nav-icon fas fa-bullseye"></i>
              <p>
                My Action Points
              </p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
  		var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		if($('.nav-link.nav-'+page).length > 0){
  			$('.nav-link.nav-'+page).addClass('active')
          console.log($('.nav-link.nav-'+page).hasClass('tree-item'))
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
          $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
      $('.manage_account').click(function(){
        uni_modal('Manage Account','manage_user?id='+$(this).attr('data-id'))
      })
  	})
  </script>