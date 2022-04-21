<?php
session_start();

if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
}
else {
	require_once('header.php');
}
?>

  <!-- MAIN CONTENT-->
  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="overview-wrap">
              <h2 class="title-1">overview</h2>
            </div>
          </div>
        </div>
        <div class="row m-t-25">
          <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c1">
              <div class="overview__inner text-center">
                <div class="overview-box clearfix p-b-15">

                  <a class="" href="upload.php">
                    <div class="icon">
                      <i class="zmdi zmdi-upload"></i>
                    </div>
                    <div class="dashboard-card-titles">
                      <p style="color: white; font-size: 15px">Upload Documents</p>
                    </div>
                  </a>

                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c3">
              <div class="overview__inner text-center">
                <div class="overview-box clearfix p-b-15">
                  <a class="" href="metatag.php">
                    <div class="icon">
                      <i class="zmdi zmdi-pages"></i>
                    </div>
                    <div class="dashboard-card-titles">
                      <p style="color: white; font-size: 15px">Meta Tag Documents</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c4">
              <div class="overview__inner text-center">
                <div class="overview-box clearfix p-b-15">
                  <a class="" href="search.php">
                    <div class="icon">
                      <i class="zmdi zmdi-search-in-page"></i>
                    </div>
                    <div class="dashboard-card-titles">
                      <p style="color: white; font-size: 15px">Search/Filter</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c4">
              <div class="overview__inner text-center">
                <div class="overview-box clearfix p-b-15">
                  <a class="" href="controlled_vocabularies.php">
                    <div class="icon">
                      <i class="zmdi zmdi-calendar-note"></i>
                    </div>
                    <div class="dashboard-card-titles">
                      <p style="color: white; font-size: 15px">Controlled Vocabularies</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c4">
              <div class="overview__inner text-center">
                <div class="overview-box clearfix p-b-15">
                  <a class="" href="users.php">
                    <div class="icon">
                      <i class="zmdi zmdi-folder-person"></i>
                    </div>
                    <div class="dashboard-card-titles">
                      <p style="color: white; font-size: 15px">Users</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php require_once 'footer.php' ?>