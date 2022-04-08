<?php
//session_start();
//$uid= $_SESSION['user_id'];

require 'db_files/db_config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sierra Leone Public Archives">
  <meta name="author" content="Sierra Leone Public Archives">
  <meta name="keywords" content="Sierra Leone Public Archives">

  <!-- Title Page-->
  <title>Sierra Leone Public Archives</title>

  <!-- Fontfaces CSS-->
  <link href="assets/css/font-face.css" rel="stylesheet" media="all">
  <link href="assets/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

  <!-- Bootstrap CSS-->
  <link href="assets/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

  <!-- Vendor CSS-->
  <link href="assets/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/wow/animate.css" rel="stylesheet" media="all">
  <link href="assets/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/slick/slick.css" rel="stylesheet" media="all">
  <link href="assets/vendor/select2/select2.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
  <link href="assets/css/filepond/filepond.css" rel="stylesheet">
  <link href="assets/css/filepond/filepond-plugin-image-preview.css" rel="stylesheet">
  <link href="assets/css/user_profile.css" rel="stylesheet" media="all">
  <!-- Main CSS-->
  <link href="assets/css/login.css" rel="stylesheet" media="all">
  <link href="assets/css/theme.css" rel="stylesheet" media="all">
  <!-- Jquery JS-->
  <script src="assets/vendor/jquery-3.2.1.min.js"></script>
</head>

<body class="animsition">
<div class="page-wrapper">
  <!-- HEADER MOBILE-->
  <header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
      <div class="container-fluid">
        <div class="header-mobile-inner">
          <a class="logo" href="index.php">
            <img src="#" alt="Sierra Leone Public Archives"/>
          </a>
          <button class="hamburger hamburger--slider" type="button">
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
            </span>
          </button>
        </div>
      </div>
    </div>
    <nav class="navbar-mobile">
      <div class="container-fluid">
        <ul class="navbar-mobile__list list-unstyled">
          <li class="has-sub">
            <a class="js-arrow" href="dashboard.php">
              <i class="fas fa-tachometer-alt"></i>Dashboard</a>
          </li>
          <li>
            <a href="upload.php">
              <i class="far fa-upload"></i>Upload Documents</a>
          </li>
          <li>
            <a href="vocabularies.php">
              <i class="far fa-dochub"></i>Meta Tag Documents</a>
          </li>
          <li>
            <a href="search.php">
              <i class="far fa-search"></i>Search/Filter</a>
          </li>
          <li>
            <a href="login.php">
              <i class="far fa-edit"></i>Edit Controlled Vocabularies</a>
          </li>
          <li>
            <a href="users.php">
              <i class="far fa-user"></i>Add Users</a>
          </li>
      </div>
    </nav>
  </header>
  <!-- END HEADER MOBILE-->

  <!-- MENU SIDEBAR-->
  <aside class="menu-sidebar d-none d-lg-block">
    <div class="logo">
      <a href="index.php">
        <img src="#" alt="Sierra Leone Public Archives"/>
      </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
      <nav class="navbar-sidebar">
        <ul class="list-unstyled navbar__list">
          <li class="active has-sub">
            <a class="js-arrow" href="dashboard.php">
              <i class="fas fa-tachometer-alt"></i>Dashboard</a>

          <li>
            <a href="upload.php">
              <i class="fas fa-upload"></i>Upload Documents</a>
          </li>
          <li>
            <a href="metatag.php">
              <i class="far fa-folder-open"></i>Meta Tag Documents</a>
          </li>

          <li>
            <a href="search.php">
              <i class="fas fa-search"></i>Search/Filter</a>
          </li>
          <li>
            <a href="vocabularies.php">
              <i class="far fa-edit"></i>Edit Controlled Vocabularies </a>
          </li>
          <li>
            <a href="users.php">
              <i class="far fa-user"></i>Users</a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <!-- END MENU SIDEBAR-->

  <!-- PAGE CONTAINER-->
  <div class="page-container">
    <!-- HEADER DESKTOP-->
    <header class="header-desktop">
      <div class="section__content section__content--p30 ">
        <div class="container-fluid ">
          <div class="header-wrap justify-content-end">
            <div class="header-button ">
              <div class="noti-wrap">
              </div>
              <div class="account-wrap">
                <div class="account-item clearfix js-item-menu">
                  <div class="image">
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="John Doe" />
                  </div>
                  <div class="content">
                    <a class="js-acc-btn" href="#"><?php echo empty($_SESSION['name']) ? $_SESSION['email'] : $_SESSION['name']; ?></a>
                  </div>
                  <div class="account-dropdown js-dropdown">
                    <div class="info clearfix">
                      <div class="image">
                        <a href="#">
                          <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="John Doe" />
                        </a>
                      </div>
                      <div class="content">
                        <h5 class="name">
                          <a href="#"><?php echo empty($_SESSION['user_name']) ? "" : $_SESSION['user_name']; ?></a>
                        </h5>
                        <span class="email"><?php echo $_SESSION['email'] ?></span>
                      </div>
                    </div>
                    <div class="account-dropdown__body">
                      <div class="account-dropdown__item">
                        <a href="user_profile.php">
                          <i class="zmdi zmdi-account"></i>Account</a>
                      </div>
                      <div class="account-dropdown__item">
                        <a href="#">
                          <i class="zmdi zmdi-settings"></i>Setting</a>
                      </div>
                    </div>
                    <div class="account-dropdown__footer">
                      <a href="logout.php">
                        <i class="zmdi zmdi-power"></i>Logout</a>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </header>