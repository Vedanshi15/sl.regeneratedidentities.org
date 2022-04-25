<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $records = $conn->prepare('SELECT * FROM users WHERE id = :id');
  $records->bindParam(':id', $_SESSION['user_id']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);
  $role = null;
  if ($results['security'] == 0) {
    $role = "Super Admin";
  } else if ($results['security'] == 1) {
    $role = "Project Director";
  } else if ($results['security'] == 2) {
    $role = "Archivists/Researcher";
  } else {
    $role = "Unknown";
  }
  require_once 'header.php';
?>
<div class="main-content">
  <div class="container user-profile-body">
  <div class="main-body">

    <div class="row gutters-sm">
      <div class="col-md-4 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center">
              <img src="assets/img/users/<?php echo $results['img'] ? $results['img'] : 'NA.jpg';?>" alt="User Picture" class="rounded-circle" width="150">
              <div class="mt-3">
                <h4><?php echo $results['fname'] . " " . $results['lname']; ?></h4>
                <p class="text-secondary mb-1"><?php echo $role;?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">First Name</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <?php echo $results['fname']; ?>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Last Name</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <?php echo $results['lname']; ?>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <?php echo $results['email']; ?>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Organization</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <?php echo $results['Organization']; ?>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-12">
                <a class="btn btn-success" href="edit_user_profile.php">Edit</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
</div>
<?php
  require_once("footer.php")
?>
