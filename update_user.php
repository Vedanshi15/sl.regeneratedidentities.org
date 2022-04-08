<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $email = $fname = $lname = $role = $organization = "";
  if (isset($_SESSION['user_id']) && $_SESSION['role'] = "Super Admin") {
    if (isset($_POST['update_user'])) {
      $id = $_POST['id'];
      $query = $conn->query("SELECT * FROM `users` where id = " . $id);
      $temp = $query->fetch(PDO::FETCH_ASSOC);
      $email = $temp['email'];
      $fname = $temp['fname'];
      $lname = $temp['lname'];
      $organization = $temp['Organization'];
      $role = $temp['security'];
    }
    if (isset($_POST['submit'])) {
      $id = $_POST['id'];
      $email = $_POST['email'];
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $role = $_POST['role'];
      $organization = $_POST['organization'];

      $sql = "Update users set
      			email = :email,
                fname = :fname,
                lname = :lname,
                Organization = :Organization,
                security = :security
                WHERE id = :id ";

      $pst = $conn->prepare($sql);
      $pst->bindParam(':email', $email);
      $pst->bindParam(':fname', $fname);
      $pst->bindParam(':lname', $lname);
      $pst->bindParam(':security', $role);
      $pst->bindParam(':Organization', $organization);
      $pst->bindParam(':id', $id);
      $count = $pst->execute();


      if ($count) {
        echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#success-dialog").modal("show");
			});
				</script>';
      } else {
        echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#error-dialog").modal("show");
			});
				</script>';
      }
    }
  } else {
    header('Location: index.php');
  }
  require_once 'header.php'
?>
  <!-- MAIN CONTENT-->
  <div class="main-content">
  <div class="section__content section__content--p30">
  <div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <strong>Update User</strong>
        </div>
        <div class="card-body card-block">
          <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="row form-group">
              <div class="col col-md-3">
                <input type="hidden" name="id" value="<?= $id; ?>"/>
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="email" class=" form-control-label">Email</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="email" id="email" name="email" placeholder="Email" value="<?= $email; ?>"
                       class="form-control">
              </div>
            </div>


            <div class="row form-group">
              <div class="col col-md-3">
                <label for="fname" class=" form-control-label">First Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="fname" id="fname" placeholder="First Name"
                       class="form-control" value="<?= $fname; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="lname" class=" form-control-label">Last Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="lname" id="lname" placeholder="Last Name"
                       class="form-control" value="<?= $lname; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="organization" class=" form-control-label">Organization</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="organization" id="organization" placeholder="Organization"
                       class="form-control" value="<?= $organization; ?>">
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3">
                <label for="role" class=" form-control-label">Role</label>
              </div>
              <div class="col-12 col-md-9">
                <select id="role" name="role" class="form-control">
                  <?php
                    if ($role == 0) {
                      $roleName = "Super Admin";
                    } else if ($role == 1) {
                      $roleName = "Project Director";
                    } else if ($role == 2) {
                      $roleName = "Archivists/Researcher";
                    } else {
                      $roleName = "Unknown";
                    }
                  ?>
                  <option value="<?php echo $role; ?>" selected><?php echo $roleName; ?></option>
                  <option value="0">Super Admin</option>
                  <option value="1">Project Director</option>
                  <option value="2">Archivists/Researcher</option>
                </select>
                <small class="form-text text-muted">Print Error</small>
              </div>
            </div>


            <div class="card-footer">
              <button type="submit" name="submit" id="submit" data-toggle="modal" class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Submit
              </button>
              <button type="reset" class="btn btn-danger btn-sm">
                <i class="fa fa-ban"></i> Reset
              </button>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="success-dialog" tabindex="-1" role="dialog" data-backdrop="false"
                 aria-labelledby="success-dialog" aria-hidden="true">
              <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content  bg-success">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">User Data Updated</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body text-dark">
                    <p>User data has been updated!</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="location.href = 'users.php';" class="btn bg-dark text-white"
                            data-dismiss="modal">Close
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="error-dialog" tabindex="-1" role="dialog" data-backdrop="false"
                 aria-labelledby="error-dialog" aria-hidden="true">
              <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content  bg-danger">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body text-dark">
                    <p>Sorry, there is an error in updating!</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="location.href = 'users.php';" class="btn bg-dark text-white"
                            data-dismiss="modal">Close
                    </button>
                  </div>
                </div>
              </div>
            </div>

        </div>

      </div>

    </div>

  </div>

<?php require_once 'footer.php' ?>