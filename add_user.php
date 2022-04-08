<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $count = null;
  if (isset($_POST['submit']) and $_SESSION['role'] = "Super Admin") {
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $organization = $_POST['organization'];
    $role = $_POST['role'];
    $sql = "INSERT INTO users (email, fname, lname, security, Organization) 
              VALUES ( :email, :fname, :lname, :security, :Organization) ";
    $pst = $conn->prepare($sql);
    $pst->bindParam(':email', $email);
    $pst->bindParam(':fname', $fname);
    $pst->bindParam(':lname', $lname);
    $pst->bindParam(':security', $role);
    $pst->bindParam(':Organization', $organization);
    $count = $pst->execute();
    if ($count) {
      echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#success-dialog").modal("show");
			});
		</script>';
    } else {
      echo "Error!!";
    }


  }
  require_once 'header.php';
?>


  <!-- MAIN CONTENT-->
  <div class="main-content">
  <div class="section__content section__content--p30">
  <div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <strong>Add User</strong>
        </div>
        <div class="card-body card-block">
          <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

            <div class="row form-group">
              <div class="col col-md-3">
                <label for="email" class=" form-control-label">Email</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="email" name="email" placeholder="Email"
                       value="<?= isset($email) ? $email : ''; ?>" class="form-control">
              </div>
            </div>


            <div class="row form-group">
              <div class="col col-md-3">
                <label for="fname" class=" form-control-label">First Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="fname" name="fname" placeholder="First Name"
                       value="<?= isset($fname) ? $fname : ''; ?>" class="form-control">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="lname" class=" form-control-label">Last Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="lname" name="lname" placeholder="Last Name"
                       value="<?= isset($lname) ? $lname : ''; ?>" class="form-control">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="organization" class=" form-control-label">Organization</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="organization" id="organization" placeholder="Organization"
                       class="form-control" value="<?= isset($organization) ? $organization : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="role" class=" form-control-label">Role</label>
              </div>
              <div class="col-12 col-md-9">
                <select id="role" name="role" class="form-control">
                  <option value="0">Super Admin</option>
                  <option value="1">Project Director</option>
                  <option value="2">Archivists/Researcher</option>
                </select>
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" name="submit" id="submit" data-toggle="modal" data-target="#success-dialog"
                      class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Submit
              </button>
              <button type="reset" class="btn btn-danger btn-sm">
                <i class="fa fa-ban"></i> Reset
              </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="success-dialog" tabindex="-1" role="dialog" aria-labelledby="success-dialog"
                 aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">User Added</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p> User has been added to the system.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="location.href = 'users.php';" class="btn btn-secondary"
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