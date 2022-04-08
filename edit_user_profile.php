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
  <div class="container">
    <div class="main-body">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                <div class="mt-3">
                  <h4><?php echo $results['fname'] . " " . $results['lname']; ?></h4>
                  <p class="text-secondary mb-1"><?php echo $role;?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-sm-3">
                  <h6 class="mb-0">First Name</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <input type="text" class="form-control" value="<?php echo $results['fname']?>">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <h6 class="mb-0">Last Name</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <input type="text" class="form-control" value="<?php echo $results['lname']?>">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <h6 class="mb-0">Email</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <input type="text" class="form-control" value="<?php echo $results['email']?>">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-3">
                  <h6 class="mb-0">Organization</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  <input type="text" class="form-control" value="<?php echo $results['Organization']?>">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9 text-secondary">
                  <input type="button" class="btn btn-primary px-4" value="Save Changes">
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
