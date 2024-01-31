  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
      <a href="#" class="brand-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <span class="brand-image img-circle elevation-3 d-flex justify-content-center align-items-center bg-primary text-white font-weight-500" style="width: 38px;height:50px"><?php echo strtoupper(substr($currUser->first_name, 0, 1) . substr($currUser->last_name, 0, 1)) ?></span>
        <span class="brand-text font-weight-light"><?php echo ucwords($currUser->first_name . ' ' . $currUser->last_name) ?></span>

      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item manage_account" href="index?page=users-profile" data-id="<?php echo $currUser->id ?>">Manage Account</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="javascript:void(0)" onclick="logout()">Logout</a>
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
            <a href="#" class="nav-link nav-is-tree nav-edit_survey nav-view_survey">
              <i class="nav-icon fa fa-poll-h"></i>
              <p>
                Checklist
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (hasPermission(PERM_CHECKLIST_MANAGEMENT, $currUser)) : ?>
                <li class="nav-item">
                  <a href="./index?page=checklists-edit" class="nav-link nav-checklists-edit tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Add New</p>
                  </a>
                </li>
              <?php endif; ?>
              <li class="nav-item">
                <a href="./index?page=checklists" class="nav-link nav-checklists tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
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
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-bullseye"></i>
              <p>
                Action Points
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index?page=action_points-assigned" class="nav-link nav-action_points-assigned tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Assigned </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index?page=action_points-created" class="nav-link nav-action_points-created tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Created</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index?page=action_points-all" class="nav-link nav-action_points-all tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>All</p>
                </a>
              </li>
            </ul>
          </li>


          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index?page=checklist_report" class="nav-link nav-checklist_report tree-item">
                  <i class="nav-icon fas fa-poll"></i>
                  <p>
                    Checklist Report
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php if ($currUser->getCategory()->access_level === 'Program') : ?>
            <li class="nav-item">
              <a href="./index?page=facilities" class="nav-link nav-facilities">
                <i class="nav-icon fa fa-building"></i>
                <p>
                  Facilities
                </p>
              </a>
            </li>
          <?php endif; ?>

          <?php if (hasPermission(PERM_USER_MANAGEMENT, $currUser)) : ?>
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
                <li class="nav-item">
                  <a href="./index?page=users-categories" class="nav-link nav-users-categories tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Categories</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>

          <?php if (hasPermission(PERM_SYSTEM_ADMINISTRATION, $currUser) && ($currUser->getCategory()->access_level === 'Program' || $currUser->getCategory()->access_level === 'System') : ?>
            <li class="nav-item">
              <a href="#" class="nav-link nav-edit_user">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Teams
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./index?page=teams-edit" class="nav-link nav-teams-edit tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Create </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index?page=teams" class="nav-link nav-teams tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>List</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Analytics
                <sup>
                  <span class='badge badge-warning rounded-pill'>Beta</span>
                </sup>
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index?page=analytics-edit" class="nav-link nav-analytics-edit tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index?page=analytics" class="nav-link nav-analytics tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <?php if ($currUser->getCategory()->access_level === 'Program') : ?>
            <li class="nav-item">
              <a href="./index?page=programs" class="nav-link nav-programs">
                <i class="nav-icon fa fa-building"></i>
                <p>
                  Programs
                </p>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
    $(document).ready(function() {
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      if ($('.nav-link.nav-' + page).length > 0) {
        $('.nav-link.nav-' + page).addClass('active')
        console.log($('.nav-link.nav-' + page).hasClass('tree-item'))
        if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
          $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
          $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
          $('.nav-link.nav-' + page).parent().addClass('menu-open')
        }

      }
    })

    const logout = () => {
      fetch('../logout')
        .then(() => window.location.reload())

    }
  </script>