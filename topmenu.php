
<div class="main-header">

  <div class="logo-header">

    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
    <center>
      <h4 class="pt-3" style="vertical-align:middle">Sierra Leone Public Archives<h4>
    </center>
  </div>

  <nav class="navbar navbar-header navbar-expand-lg">
    <div class="container-fluid">
      <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
        <li class="nav-item dropdown">
          <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
            <span><?php echo empty($_SESSION['name']) ? $_SESSION['email'] : $_SESSION['name']; ?></span>
          </a>
          <ul class="dropdown-menu dropdown-user">
            <li>
              <div class="user-box">
                <div class="u-text" style="word-break: break-word;">
                  <h4><?php echo empty($_SESSION['name']) ? "" : $_SESSION['name']; ?></h4>
                  <p class="text-muted">
                    <?php echo $_SESSION['email'] ?>
                  </p><a href="profile.php" class="btn btn-rounded btn-danger btn-sm">View Profile</a>
                  <p style="margin-top: 5px;">
                    User Role : <?php echo $_SESSION['role']; ?>
                  </p>
                </div>
              </div>
            </li>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php"><i class="la la-power-off"></i> Logout</a>
          </ul>
          <!-- /.dropdown-user -->
        </li>
      </ul>
    </div>
  </nav>
</div>

