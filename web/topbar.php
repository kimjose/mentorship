<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-primary navbar-dark ">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li>
      <a class="nav-link text-white" href="./" role="button">
        <large><b>eSupport Supervision</b></large>
      </a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <sup>
        <span class="badge badge-danger badge-counter" id="badgeNotifications">3+</span>
        </sup>
      </a>
      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
         <b> Notifications Center </b>
        </h6>
        <div id="divNotificationList" style="overflow-y: auto; max-height: 300px">
          <div class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
              <button class="icon-circle bg-success" title="Mark as read" data-placement="bottom">
                <i class="fas fa-envelope-open text-white"></i>
              </button>
            </div>
            <div>
              <div class="small text-gray-500">December 7, 2019</div>
              $290.29 has been deposited into your account!
            </div>
          </div>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
              <div class="icon-circle bg-warning">
                <i class="fas fa-exclamation-triangle text-white"></i>
              </div>
            </div>
            <div>
              <div class="small text-gray-500">December 2, 2019</div>
              Spending Alert: We've noticed unusually high spending for your account.
            </div>
          </a>
        </div>

        <a class="dropdown-item text-center text-black" href="#" onclick="markAllAsRead()">Mark all as read</a>
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>


    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<script>
  const badgeNotifications = document.getElementById('badgeNotifications')
  const divNotificationList = document.getElementById('divNotificationList')
  const user = JSON.parse('<?php echo $currUser; ?>')

  function initializeTopNav() {
    fetch('../api/notifications/' + user.id)
      .then(response => {
        return response.json()
      })
      .then(response => {
        if (response.code === 200) {
          // window.location.reload()
          loadNotifications(response.data)
        } else throw new Error(response.message)
        // hideModal(dialogId)
      })
      .catch(error => {
        console.log(error.message);
        // alert(error.message)
        toastr.error(error.message)
      })
  }

  function loadNotifications(notifications) {
    badgeNotifications.innerText = notifications.length
    if (notifications.length > 0) {
      toastr.info(`You have ${notifications.length} unread notifications.`)
    }
    divNotificationList.innerHTML = ""
    notifications.forEach(notification => {
      let container = document.createElement('div')
      container.classList.add('dropdown-item', 'd-flex', 'align-items-center');
      let iconContainer = document.createElement('div')
      iconContainer.classList.add('mr-3')
      let btnIcon = document.createElement('button')
      btnIcon.classList.add('icon-circle', 'bg-success')
      btnIcon.setAttribute('title', 'Mark as read')
      btnIcon.setAttribute('data-placement', 'bottom')
      btnIcon.addEventListener('click', () => markAsRead(notification.id))
      btnIcon.innerHTML = '<i class="fas fa-envelope-open text-white"></i>'
      iconContainer.appendChild(btnIcon)
      container.appendChild(iconContainer)
      let contentContainer = document.createElement('div')
      let dateContainer = document.createElement('div')
      dateContainer.classList.add('small', 'text-gray-500')
      dateContainer.innerText = DateFormatter.formatDate(new Date(notification.created_at), 'YYYY-MM-DD hh:mm')
      let contentMessage = document.createElement('span')
      contentMessage.innerText = notification.message;
      contentContainer.appendChild(dateContainer)
      contentContainer.appendChild(contentMessage)
      container.appendChild(contentContainer)
      divNotificationList.appendChild(container)
    })
  }

  function markAsRead(id) {
    let data = {
      id: id
    }
    fetch(
        '../api/notification/read', {

          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        }
      )
      .then(response => {
        return response.json()
      })
      .then(response => {
        if (response.code === 200) {
          loadNotifications(response.data)
        } else throw new Error(response.message)
        // hideModal(dialogId)
      })
      .catch(error => {
        console.log(error.message);
        alert(error.message)
        toastr.error(error.message)
      })
  }

  function markAllAsRead() {
    if (divNotificationList.children.length === 0) return
    let data = {
      user_id: user.id
    }
    fetch(
        '../api/notifications/read', {

          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        }
      )
      .then(response => {
        return response.json()
      })
      .then(response => {
        if (response.code === 200) {
          loadNotifications(response.data)
        } else throw new Error(response.message)
        // hideModal(dialogId)
      })
      .catch(error => {
        console.log(error.message);
        toastr.error(error.message)
      })
  }

  initializeTopNav()
</script>